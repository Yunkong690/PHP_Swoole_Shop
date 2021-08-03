<?php


//引入系统类,基于PDO实现
//use Core\PDO, Core\PDOStatement, Core\PDOException;

//定义类
class DbUtils
{
    public $error; //记录错误信息
    public $pdo;   //报错pdo类对象
    private $fetch_mode;   //查询数据的模式：默认为关联数组

    public function __construct($database_info = [], &$drivers = [])
    {
        $type = isset($database_info['type']) ? $database_info['type'] : 'mysql';
        $host = isset($database_info['host']) ? $database_info['host'] : '127.0.0.1';
        $port = isset($database_info['port']) ? $database_info['port'] : '3306';
        $user = isset($database_info['user']) ? $database_info['user'] : 'root';
        $pass = isset($database_info['pass']) ? $database_info['pass'] : 'Sjx1457';
        $dbname = isset($database_info['dbname']) ? $database_info['dbname'] : 'php_shop';
        $charset = isset($database_info['charset']) ? $database_info['charset'] : 'utf8';

        #fetchcode不能在初始化的时候实现，需要在得到PDOStatement类对象后设置
        $this->fetch_mode = isset($drivers[PDO::ATTR_DEFAULT_FETCH_MODE]) ? $drivers[PDO::ATTR_DEFAULT_FETCH_MODE] : PDO::FETCH_ASSOC;

        //控制属性
        if (!isset($drivers[PDO::ATTR_ERRMODE])) {
            $drivers[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
        }

        //连接认证
        try {
            //增加错误抑制符防止意外
            $this->pdo = @new PDO($type . ':host=' . $host . ';port=' . $port . ';dbname=' . $dbname . ';charset=' . $charset, $user, $pass, $drivers);

        } catch (PDOException $e) {
            //调用异常处理方法，实现异常处理
            $this->my_exception($e);
        }
    }

    private function my_exception(PDOException $e)
    {
        $this->error['file'] = $e->getFile();
        $this->error['line'] = $e->getLine();
        $this->error['error'] = $e->getMessage();
        return false;
    }

//写操作
    public function my_exec($sql, $array)
    {
        try {
            //预处理防止sql注入
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($array);
            //设置查询模式
            $stmt->setFetchMode($this->fetch_mode);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            return $this->my_exception($e);
        }
    }

//获取自增长ID
    public function my_last_insert_id()
    {
        try {
            $id = $this->pdo->lastInsertId();
            if (!$id) throw new PDOException('自增长Id不存在');
            return $id;
        } catch (PDOException $e) {
            return $this->my_exception($e);
        }
    }

//读操作
    public function my_query($sql, $array, $only = true)
    {
        try {
            //预处理防止sql注入
            $stmt = $this->pdo->prepare($sql);
            if ($array != "") {
                $stmt->execute($array);
            } else {
                $stmt->execute();
            }
            //设置查询模式
            $stmt->setFetchMode($this->fetch_mode);

        } catch (PDOException $e) {
            return $this->my_exception($e);
        }
        //数据解析
        if ($only) {
            return $stmt->fetch();
        } else {

            return $stmt->fetchAll();
        }

    }

    public function Transaction($array)
    {

        $this->pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, false);
        $flag=false;
        try {
            $this->pdo->beginTransaction(); // 开启一个事务\

            foreach($array as $row){
                $res =  $this->pdo->exec($row);// 执行SQL
                if($res){
                    $flag=true;
                }else{
                    $flag=false;
                }
            }
            if($flag){
                $this->pdo->commit();
            }
//            $row = null;
//            $row =  $this->pdo->exec($sql);
//            if (!$row)
//                throw new PDOException('提示信息或执行动作'); // 如出现异常提示信息或执行动作
//            $row = $this->pdo->exec("xxx"); // 执行第二个 SQL
        } catch (PDOException $e) {
            $this->pdo->rollback(); // 执行失败，事务回滚
           return $flag;
        }

    }
}

//$test=new DbUtils();
//$array=[
//    "update user set money =1000 where id=1",
//    "update goods set num =50 where id=49"
//];
//$test->Transaction($array)''
//$sql="select username,nickname,sex,address,tel,name,money,status  from user limit "."0";
//$res=$m->my_query($sql,"",false);
////print_r ($res);
//$res = $m->my_exec('update admin set username=? , name =? , tel =? , note=? where id=?', array("admin", "zs", "122223", "", 1));
//
//$res = $m->my_query('select *  from admin limit ?', array(1));
//print_r ($res);

