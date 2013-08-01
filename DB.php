<?php
##########################################
##########################################
class DB{
  var $db_host;
  var $db_user;
  var $db_password;
  var $db_database;
  var $result;
  var $conn;
  var $sql;
  var $row;
  var $connpara;
  //构造空函数
  function DB($db_host,$db_user,$db_password,$db_database){
    $this->db_host = $db_host;
    $this->db_user = $db_user;
    $this->db_password = $db_password;
    $this->db_database = $db_database;
    $this->connpara = "host =".$db_host." port =5432 dbname=".$db_database." user=".$db_user." password=".$db_password;
  }

  //连接数据库
  function conn(){
    //$connpara="host=localhost port=5432 dbname=flavidb user=browser password=brw0nly";
    

    $conn= pg_connect($this->connpara);
    
    //$conn = mysql_connect($this->db_host,$this->db_user,$this->db_password);
    if(!$conn){
      $this->msg_error("无法连接数据库");
    }else{

      $this->conn = $conn;
    }
   
    return $this->conn;
  }

  //更新数据库,包括数据添加,更新
  function query_noResult($sql){
    if("" == $sql){$this->msg_error("传递了一个空变量");}
    $this->sql = $sql;
    //echo $this->sql;
    //echo $this->conn;
    //$result = pg_query($conn,$sql);
    //$amount=pg_num_rows($result);
    if (!pg_query($this->conn,$this->sql)){
       $this->msg_error(("数据更新过程发生错误!"));
    }
    //if(!mysql_db_query($this->db_database,$this->sql,$this->conn)){
    // $this->msg_error(("数据更新过程发生错误!"));
    //}
    return null;
  } 
 


//查询数据并返回result类型
  function query_withResult($sql){
    if("" == $sql){$this->msg_error("传递了一个空变量");}
    $this->sql = $sql;

    $result = pg_query($this->conn, $this->sql);
    $num = pg_num_rows($result);

    if(!$result){
      $this->msg_error("数据查询过程发生错误或空记录!");
    }else{
      $this->result = $result;
    }
    //使用时,可以直接引用成员变量而无须关心返回值,除非需要返回值
    //做更多处理
    return $this->result;
  }

  //释放结果集
  function free(){
    return pg_free_result($this->result);
  }

  //关闭连接
  function close(){
    if($this->result){
      $this->free();
    }
    pg_close($this->conn);
  }


  // 根据执行结果取得影响行数
  function db_affected_rows(){
    return pg_affected_rows();
  }


  // 根据查询结果计算结果集条数
  function db_num_rows(){
    if($this->result==null){
      $this->msg_error("记录为空!");
    }
    return pg_num_rows($this->result);
  }


  //取得记录集 
  function db_fetch_array(){
    if($this->result==null){
      $this->msg_error("您查询的记录为空!");
    }

    while($row=pg_fetch_array($this->result)){
        $this->row = $row;
        //echo print_r($this->row);
    }
    //return $this->row;
  }

  //指向确定的一条数据记录
  //通用数据库错误处理
  function db_data_seek($result,$i){
    //mysql_data_seek($result,$i);
    pg_result_seek($result, $i);
    $row = pg_fetch_row($result);
    return $row;
  }


  function msg_error($message){
    $time  = date("Y年m月d日 H时i分s秒");
    echo "很抱歉您操作数据库时发生了错误<br>";
    echo "发生错误时间:".$time."<br>";
    echo "错误信息:".$message."<br>";
    echo "请发送邮件到<a href=mailto:wangjun19820303@163.com>wangjun19820303@163.com</a>..谢谢!";
    exit();
 }
}
?>
