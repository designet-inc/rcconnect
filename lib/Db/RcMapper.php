<?php
namespace OCA\RcConnect\Db;

use OCP\AppFramework\Db\DoesNotExistException;
use OCP\AppFramework\Db\Entity;
use OCP\AppFramework\Db\QBMapper;
use OCP\DB\QueryBuilder\IQueryBuilder;
use OCP\IDBConnection;

class RcMapper extends QBMapper {
    public function __construct(IDBConnection $db) {
    parent::__construct($db, 'rcconnect', Rc::class);
    }

    public function getUserInfo($uid){
        $this->db->beginTransaction();
        try {
            $qb = $this->db->getQueryBuilder();

            // UIDにマッチするデータの数を取得
            $qb->selectAlias($qb->createFunction('COUNT(*)'), 'count')
                ->from('rcconnect')
                ->where("uid ="."'".$uid."'");

            // SQLを実行
            $cursor = $qb->execute();

            // 結果の配列を取得
            $row = $cursor->fetch();

            // データ数が0ならtrueを返す
            if ((int)$row["count"] === 0) {
                $cursor->closeCursor();
                $this->db->commit();
                return true;

            // データ数が1ならSELECTの結果を返す
            } elseif ((int)$row["count"] === 1) {
 
                $qb = $this->db->getQueryBuilder();
                $qb->select('password', 'username')
                    ->from('rcconnect')
                    ->where($qb->expr()->eq('uid', $qb->createNamedParameter($uid, IQueryBuilder::PARAM_STR)));
                $userdata = $this->findEntity($qb);
            } else {
                // 0でも1でもない場合はエラーを投げる
                $cursor->closeCursor();
                $msg = $this->buildDebugMessage(
                    'Multiple results found at run time', $qb
                );
                throw new MultipleObjectsReturnedException($msg);
            }

            // commitを行う
            $cursor->closeCursor();
            $this->db->commit();

        } catch (\Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
        return $userdata;
    }

    public function UserUpdate($uid, $username, $password){
        $this->db->beginTransaction();
        try {
            $qb = $this->db->getQueryBuilder();

            // UIDにマッチするデータの数を取得
            $qb->selectAlias($qb->createFunction('COUNT(*)'), 'count')
                ->from('rcconnect')
                ->where("uid ="."'".$uid."'");

            // SQLを実行
            $cursor = $qb->execute();

            // 結果の配列を取得
            $row = $cursor->fetch();

            // データ数が0なら新規で追加する
            if ((int)$row["count"] === 0) {
                $qb->insert('rcconnect')
                ->values(
                    array(
                        'uid' => $qb->createNamedParameter($uid, IQueryBuilder::PARAM_STR),
                        'username' => $qb->createNamedParameter($username, IQueryBuilder::PARAM_STR),
                        'password' => $qb->createNamedParameter($password, IQueryBuilder::PARAM_STR)
                    )
                );
                $qb->execute();

                // データ数が1の場合はupdate
            } elseif ((int)$row["count"] === 1) {
                $qb->update('rcconnect');
                $qb->set('username', $qb->createNamedParameter($username, IQueryBuilder::PARAM_STR),);
                $qb->set('password',$qb->createNamedParameter($password, IQueryBuilder::PARAM_STR));
                $qb->where($qb->expr()->eq('uid', $qb->createNamedParameter($uid, IQueryBuilder::PARAM_STR)));
                $qb->execute();

            } else {
                // 0でも1でもない場合はエラーを投げる
                $cursor->closeCursor();
                $msg = $this->buildDebugMessage(
                    'Multiple results found at run time', $qb
                );
                throw new MultipleObjectsReturnedException($msg);
            }

            // commitを行う
            $cursor->closeCursor();
            $this->db->commit();
        } catch (\Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
        return true;
    }

    public function UserDelete($uid){
        $this->db->beginTransaction();
        try {
            $qb = $this->db->getQueryBuilder();

            $qb->select('*')
                ->from('rcconnect')
                ->where($qb->expr()->eq('uid', $qb->createNamedParameter($uid, IQueryBuilder::PARAM_STR)));
 
            // UIDにマッチするデータの数を取得
            $qb->selectAlias($qb->createFunction('COUNT(*)'), 'count')
                ->from('rcconnect')
                ->where("uid ="."'".$uid."'");

            // SQLを実行
            $cursor = $qb->execute();

            // 結果の配列を取得
            $data_arr = $cursor->fetch();

            // データ数が1なら削除する
            if ((int)$data_arr["count"] === 1) {
                $qb->delete('rcconnect');
                $qb->where($qb->expr()->eq('uid', $qb->createNamedParameter($uid, IQueryBuilder::PARAM_STR)));
                $qb->setParameter(':id', $qb->createNamedParameter($data_arr["id"], IQueryBuilder::PARAM_INT));
                $qb->execute();

            } elseif ((int)$data_arr["count"] === 0) {
                $cursor->closeCursor();
                $this->db->commit();
                return true;
            } else {
                // データ数が0でも1でもないならエラーにする
                $cursor->closeCursor();
                $msg = $this->buildDebugMessage(
                    'Multiple results found at run time', $qb
                );
                throw new MultipleObjectsReturnedException($msg);
            }

            // commitを行う
            $cursor->closeCursor();
            $this->db->commit();

        } catch (\Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
        return true;
 
    }

}
