# classe-DB
classe que executar as operaçoes basica(SELECT ,INSERT,UPDATE,DELETE) do banco de dados mysql

retorna o id que foi cadastrado
#echo DB::insert('user',['nome'=>'felipe','idade'=>23,'local'=>'RJ']);

retorna todos os dados da tabela user
DB::get('user')


retorna o numero de linas afetadas 
#$update = ['nome' =>'lucas','idade' =>12,'local'=>'RJ'];
#$where = ['id' => 5];
#echo DB::update('user',$update,$where);



APAGAR TODOS OS REGISTRO DA TABELA.
DB::limpar('user');

REAliza a paginação 
$offset = 1;
$limit = 2;
#echo DB::pagination('user',$limit,$offset);

EXECUTAR O SELECT
#DB::select_query('SELECT * FROM user')

EXECUTAR O DELETE
#DB::delete_query('DELETE FROM user WHERE id = ?',[5]);

EXECUTAR O UPDATE
#DB::update_query('UPDATE user SET nome = ?,idade =? WHERE id=?',['nome teste',2367,4])

EXECUTAR O INSERT 
#DB::insert_query("INSERT INTO user (nome,idades,locals) VALUES(?,?,?)",['nome-teste',2222,'MG']);






