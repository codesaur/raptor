<?php namespace Indoraptor\Helper;

class WebsiteController extends \Indoraptor\IndoController
{    
    public function general($alias, $flag)
    {
        if ( ! $this->accept()) {
            return $this->error('Not Allowed');
        }
        
        $data = array();
        
        try {
            $query_settings = 'SELECT id,favico,title,phone,contact,address,open_hours,copyright,email ' .
                    'FROM settings as p INNER JOIN settings_content as c ON p.id = c.t_id  WHERE c.code = :code AND p.alias = :alias LIMIT 1';
            $stmt_settings = $this->conn->prepare($query_settings);
            $stmt_settings->bindParam(':code', $flag, \PDO::PARAM_STR, 6);
            $stmt_settings->bindParam(':alias', $alias, \PDO::PARAM_STR, 16);
            $stmt_settings->execute();
            if ($stmt_settings->rowCount() == 1) {
                $data += $stmt_settings->fetch(\PDO::FETCH_ASSOC);
            }
        } catch (\Exception $e) {
            if (DEBUG) {
                \error_log($e->getMessage());
            }
        }

        try {
            $stmt_socials = $this->conn->prepare('SELECT facebook FROM socials WHERE alias = :alias LIMIT 1');
            $stmt_socials->bindParam(':alias', $alias, \PDO::PARAM_STR, 16);
            $stmt_socials->execute();
            if ($stmt_socials->rowCount() == 1) {
                $data += $stmt_socials->fetch(\PDO::FETCH_ASSOC);
            }
        } catch (\Exception $e) {
            if (DEBUG) {
                \error_log($e->getMessage());
            }
        }
        
        if (isset($data['id'])) {
            try {
                $query_files = 'SELECT f.path as image ' .
                        'FROM file as f INNER JOIN settings_files as fs ON f.id = fs.file ' .
                        'WHERE fs.record = :id AND fs.code = :code AND fs.type = :type LIMIT 1';
                $stmt_files = $this->conn->prepare($query_files);
                $stmt_files->bindParam(':code', $flag, \PDO::PARAM_STR, 6);
                $stmt_files->bindParam(':id', $data['id'], \PDO::PARAM_INT);

                $type1 = \getenv('IMAGE_MAIN') ?: 1;
                $stmt_files->bindParam(':type', $type1, \PDO::PARAM_INT);
                $stmt_files->execute();
                if ($stmt_files->rowCount() == 1) {
                    $data['logo'] = $stmt_files->fetch(\PDO::FETCH_ASSOC)['image'];
                }

                $type2 = \getenv('IMAGE_LOGO_SMALL') ?: 2;
                $stmt_files->bindParam(':type', $type2, \PDO::PARAM_INT);
                $stmt_files->execute();
                if ($stmt_files->rowCount() == 1) {
                    $data['logo-small'] = $stmt_files->fetch(\PDO::FETCH_ASSOC)['image'];
                }

                $type6 = \getenv('IMAGE_APPLE_TOUCH') ?: 6;
                $stmt_files->bindParam(':type', $type6, \PDO::PARAM_INT);
                $stmt_files->execute();
                if ($stmt_files->rowCount() == 1) {
                    $data['apple-touch-icon'] = $stmt_files->fetch(\PDO::FETCH_ASSOC)['image'];
                }

                $type7 = \getenv('IMAGE_ICO_PNG') ?: 7;
                $stmt_files->bindParam(':type', $type7, \PDO::PARAM_INT);
                $stmt_files->execute();
                if ($stmt_files->rowCount() == 1) {
                    $data['ico-png'] = $stmt_files->fetch(\PDO::FETCH_ASSOC)['image'];
                }

                $type8 = \getenv('IMAGE_BACKGROUND') ?: 8;
                $stmt_files->bindParam(':type', $type8, \PDO::PARAM_INT);
                $stmt_files->execute();
                if ($stmt_files->rowCount() == 1) {
                    $data['background'] = $stmt_files->fetch(\PDO::FETCH_ASSOC)['image'];
                }
            } catch (\Exception $e) {
                if (DEBUG) {
                    \error_log($e->getMessage());
                }
            }
        }
        
        if (empty($data)) {
            return $this->error('Not Found!');
        } else {
            return $this->respond(array('result' => $data));
        }
    }
    
    public function menu($alias, $flag)
    {
        if ( ! $this->accept()) {
            return $this->error('Not Allowed');
        }
        
        $data = array();
        
        try {
            $query_menu = 'SELECT id,parent_id,title,menu_type,width,route,hotlink,position ' .
                    "FROM {$alias}_pages as p INNER JOIN {$alias}_pages_content as c ON p.id = c.t_id " .
                    "WHERE c.code = :code AND p.type = 'menu' AND p.published = 1 AND p.is_active = 1 AND c.status = 1 ORDER By parent_id,position,id";
            $stmt_menu = $this->conn->prepare($query_menu);
            $stmt_menu->bindParam(':code', $flag, \PDO::PARAM_STR, 6);
            $stmt_menu->execute();
            if ($stmt_menu->rowCount()) {
                while ($row = $stmt_menu->fetch(\PDO::FETCH_ASSOC)) {
                    $data[$row['id']] = $row;
                }
            }
        } catch (\Exception $e) {
            if (DEBUG) {
                \error_log($e->getMessage());
            }
        }
        
        if (empty($data)) {
            return $this->error('Not Found!');
        } else {
            return $this->respond(array('result' => $data));
        }
    }
    
    public function page($alias, $flag, $id)
    {
        if ( ! $this->accept()) {
            return $this->error('Not Allowed');
        }

        try {
            $query_menu = 'SELECT id,parent_id,title,short,full ' .
                    "FROM {$alias}_pages as p INNER JOIN {$alias}_pages_content as c ON p.id = c.t_id " .
                    "WHERE p.id = :id AND c.code = :code AND p.published = 1 AND p.is_active = 1 AND c.status = 1 LIMIT 1";
            $stmt_menu = $this->conn->prepare($query_menu);
            $stmt_menu->bindParam(':id', $id, \PDO::PARAM_INT);
            $stmt_menu->bindParam(':code', $flag, \PDO::PARAM_STR, 6);
            $stmt_menu->execute();
            if ($stmt_menu->rowCount() == 1) {
                $data = $stmt_menu->fetch(\PDO::FETCH_ASSOC);
            }
        } catch (\Exception $e) {
            if (DEBUG) {
                \error_log($e->getMessage());
            }
        }
        
        if ( ! isset($data)) {
            return $this->error('Not Found!');
        } else {
            return $this->respond(array('result' => $data));
        }
    }
}
