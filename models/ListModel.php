<?php

class ListModel
{

    public static function getList()
    {
        $db = Db::getConnection();
        $result = $db->query('SELECT * FROM list');
        $listArray = array();
        $i = 0;
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $listArray[$i]['id'] = $row['id'];
            $listArray[$i]['name'] = $row['name'];
            $listArray[$i]['description'] = $row['description'];
            $listArray[$i]['visibility'] = $row['visibility'];
            $i++;
        }
        $result = array();
        $result['TotalElement'] = $i;
        $result['content'] = $listArray;
        return $result;
    }

    public static function getById($id) {
        $db = Db::getConnection();
        $sql = 'SELECT * FROM list WHERE id = :id';
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();
        return $result->fetch();
    }

    public static function create($lsit) {
        $db = Db::getConnection();
        $sql = 'INSERT INTO list (name, description, visibility) '
                . 'VALUES (:name, :description, :visibility)';
        $result = $db->prepare($sql);
        $result->bindParam(':name', $lsit['name'], PDO::PARAM_STR);
        $result->bindParam(':description', $lsit['description'], PDO::PARAM_STR);
        $result->bindParam(':visibility', $lsit['visibility'], PDO::PARAM_STR);
        if ($result->execute()) {
            return $db->lastInsertId();
        }
        return 0;
    }

    public static function edit($list) {
        $db = Db::getConnection();
        $sql = "UPDATE list SET name = :name, description = :description, visibility = :visibility WHERE id = :id";
        $result = $db->prepare($sql);
        $result->bindParam(':id', $list['id'], PDO::PARAM_INT);
        $result->bindParam(':name', $list['name'], PDO::PARAM_STR);
        $result->bindParam(':description', $list['description'], PDO::PARAM_STR);
        $result->bindParam(':visibility', $list['visibility'], PDO::PARAM_STR);
        return $result->execute();
    }

    public static function deleteById($id) {
        $db = Db::getConnection();
        $sql = 'DELETE FROM list WHERE id = :id';
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        return $result->execute();
    }

    public static function editStaus($list) {
        $db = Db::getConnection();
        $sql = "UPDATE list SET visibility = :visibility WHERE id = :id";
        $result = $db->prepare($sql);
        $result->bindParam(':id', $list['id'], PDO::PARAM_INT);
        $result->bindParam(':visibility', $list['visibility'], PDO::PARAM_STR);
        return $result->execute();
    }

}
