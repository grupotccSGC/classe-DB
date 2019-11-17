<?php 
 
class DB{

private static $con;



public function __construct(){
$db = require __Dir__.'/DB_config.php';
#'mysql:host=localhost;dbname=tetse;charset=utf8','root',''
 $url = $db['drive'].':host='.$db['host'].';dbname='.$db['database'].';charset='.$db['charset'];
	

	try {
		Self::$con = new PDO($url,$db['user'],$db['password']);
		
		Self::$con->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

		#Self::$con->exec("set names utf8");


	} catch (PDOException $e) {
		die($e->getMessage());
	}

}



public static function getConexao(){
	if(!Self::$con){
     $c = new DB();
	}

	return Self::$con;
}



#insert into tabela(campos1,campos2,campos3)values(?,?,?)
#['nome' => '']
public static function insert($tabela,array $dados){
   $campos= '';
   $indice = '';
   $conte = [];
   $con = DB::getConexao();
	foreach ($dados as $key => $value) {
		$campos.= $campos ? ','.$key: $key;
		$indice.= $indice ? ',?' :'?';
		array_push($conte,$value);
}

$sql = "INSERT INTO {$tabela}({$campos}) VALUES({$indice})";
$smt = $con->prepare($sql);

try {
	$smt->execute($conte);
	return $con->lastInsertId();
}catch (Exception $e) {
	die($e->getMessage());
}

}



public static function get($tabela){
	$con = DB::getConexao();
	$sql = "SELECT * FROM {$tabela}";
	$smt = $con->prepare($sql);
	
try {
	$smt->execute();
	return $smt->fetchAll(PDO::FETCH_OBJ);
}catch (PDOException $e) {
	die($e->getMessage());
}

}


# Retorna o número de linhas afetadas pela última instrução SQL
public static function update($tabela,array $dados,array $where){
	$cont = 0;
	$campos ='';
	$condi ='';
	$conte = [];
	$con = DB::getConexao();
	foreach ($dados as $key => $value) {
		$campos.= $campos ? ",{$key} = ?":"{$key} = ?";
		$conte[$cont]= $value;
		$cont++;
	}

	foreach ($where as $key => $value) {
	$condi.= $condi ? " and {$key} = ?" :"{$key} = ?";
	array_push($conte, $value);
	}

  	$sql = "UPDATE {$tabela} SET {$campos} WHERE {$condi} limit 1";
 
	 $smt = $con->prepare($sql);

try {
	 $smt->execute($conte);
	 return $smt->rowCount();

}catch (PDOException $e) {
	die($e->getMessage());
}

}	 

# Retorna o número de linhas afetadas pela última instrução SQL
public static function delete($tabela,array $where){
	$con = DB::getConexao();
	$condi='';
	$conte =[];
	$count = 0;
	foreach ($where as $key => $value) {
	$condi.= $condi ? " AND {$key}=?":"{$key}=?";
	$conte[$count] = $value;
	$count++;
	}
	
	$sql = "DELETE FROM {$tabela} WHERE $condi";
	$smt = $con->prepare($sql);
	
try {
	$smt->execute($conte);
	return $smt->rowCount();
}catch (PDOException $e) {
	die($e->getMessage());
}

}
	

#APAGAR TODOS OS REGISTRO DA TABELA
 public static function limpar($tabela){
	$con = DB::getConexao();
	$sql = "TRUNCATE TABLE {$tabela}";
	return $con->exec($sql);

 }


public static function pagination($tabela,$limit,$offset){
$con = DB::getConexao();
$dados = [];
$offset = $limit * ($offset - 1);
$sql = "SELECT * FROM {$tabela} LIMIT {$limit} OFFSET {$offset}";

$smt = $con->prepare($sql);
$smt->execute();
$dados['data'] = $smt->fetchAll(PDO::FETCH_OBJ);
$smt = $con->prepare("SELECT COUNT(*) as total FROM {$tabela}");
$smt->execute();
$total = $smt->fetch();
$dados['total_itens']=$total['total'];
return json_encode($dados);

}



public static function insert_query($sql,array $dados){
	$con = DB::getConexao();
	$smt = $con->prepare($sql);
	
	try {
		$smt->execute($dados);
		return $con->lastInsertId();
		
	} catch (PDOException $e) {
		die($e->getMessage());
		   
	}

}


public static function update_query($sql,array $update){
$con = DB::getConexao();
$smt = $con->prepare($sql);
try {
	$smt->execute($update);
	return $smt->rowCount();

}catch (PDOException $e) {
	die($e->getMessage());
}



}


public static function delete_query($sql,array $where){
$con = DB::getConexao();
$smt = $con->prepare($sql);

try {
	$smt->execute($where);
	return $smt->rowCount();
}catch (PDOException $e) {
	die($e->getMessage());

}
}

public static function select_query($sql,array $dados = null ){
	$con = DB::getConexao();
	$smt = $con->prepare($sql);
	
try {
	if(is_array($dados)&& count($dados) > 0){
		$smt->execute($dados);
	}else{
		$smt->execute();
	}
	return $smt->fetchAll(PDO::FETCH_OBJ);

}catch (PDOException $e) {
	die($e->getMessage());

}

}



}


#$a =new DB();
#DB::getConexao();
#echo DB::insert('user',['nome'=>'rafael','idade'=>23,'local'=>'RJ']);

#var_dump(DB::get('user'));
#$update = ['nome' =>'lucas','idade' =>12,'local'=>'RJ'];
#$where = ['id' => 5];
#echo DB::update('user',$update,$where);
#echo DB::limpar('user');
#echo DB::pagination('user',2,1);
#var_dump(DB::select_query('SELECT * FROM user'));

#echo DB::delete_query('DELETE FROM user WHERE id = ?',[5]);

#echo DB::update_query('UPDATE user SET nome = ?,idade =? WHERE id=?',['nome teste',2367,4]);

#echo DB::insert_query("INSERT INTO user (nome,idades,locals) VALUES(?,?,?)",['nome-teste',2222,'MG']);