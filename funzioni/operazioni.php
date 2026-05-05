<?php

class Operazioni{
    private PDO $conn;
    private $whitelist=["corsi","iscrizioni_corsi","membri","istruttori"];
     
    function __construct(){
        $conf=require("conf.php");

        try{
            $this->conn= new PDO("mysql: host=".$conf["host"]."; dbname=".$conf["dbname"],$conf["user"],$conf["psw"]);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        }catch(PDOException $e){
            throw new Exception($e->getMessage());
        }
    }

    function query($table,$where=[],$groupBy=[],$having=[],$orderBy=[],$select=['*']){
        if(!in_array($table, $this->whitelist)) throw new Exception("Tabella non trovata");
        $valori = [];
        $sql = "SELECT ".implode(",", array_map(fn($c) => $c=='*' ? '*' : "`$c`", $select))." FROM `$table`";
    
        if(!empty($where)) {
            $sql=$sql." WHERE ";
            $condizioni_where = [];
            foreach($where as $k=>$v) {
                $condizioni_where[]="`$k`=:w_$k";
                $valori[":w_$k"]=$v;
            }
            $sql .= implode(" AND ", $condizioni_where);
        }
        if(!empty($groupBy)) $sql=$sql." GROUP BY ".implode(",", array_map(fn($c) => "`$c`", $groupBy));
        if(!empty($having)) {
            $sql=$sql." HAVING ";
            $condizioni_having=[];
            foreach($having as $k=>$v) {
                $condizioni_having[] = "`$k`=:h_$k";
                $valori[":h_$k"] = $v;
            }
            $sql .= implode(" AND ", $condizioni_having);
        }
        if(!empty($orderBy)) {
            $orders=[];
            foreach($orderBy as $k => $dir) {
                $dir=strtoupper($dir)=='DESC' ? 'DESC' : 'ASC';
                $orders[]="`$k` $dir";
            }
            $sql=$sql." ORDER BY " . implode(",", $orders);
        }
        $sql=$sql.";";
    
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($valori);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
