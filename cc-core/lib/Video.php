<?php

class Video {

    public $found;
    private $db;
    protected static $table = 'videos';
    protected static $id_name = 'video_id';



    /**
     * Instantiate object
     * @param integer $id ID of record to be instantiated
     * @return object Returns object of class type
     */
    public function  __construct ($id) {
        $this->db = Database::GetInstance();
        if (self::Exist (array (self::$id_name => $id))) {
            $this->Get ($id);
            $this->found = true;
        } else {
            $this->found = false;
        }
    }



    /**
     * Extract values from database and set them to object properties
     * @param integer $id ID of record to be instantiated
     * @return void DB record's fields are loaded into object properties
     */
    private function Get ($id) {
        $query = "SELECT " . DB_PREFIX . self::$table . ".*, username FROM " . DB_PREFIX . self::$table . " INNER JOIN " . DB_PREFIX . "users on " . DB_PREFIX . self::$table . ".user_id = " . DB_PREFIX . "users.user_id WHERE " . self::$id_name . "= $id";
        $result = $this->db->Query ($query);
        $row = $this->db->FetchAssoc ($result);
        foreach ($row as $key => $value) {
            $this->$key = $value;
        }

        // Video Specific values
        $this->tags = explode (' ',$this->tags);
        $this->duration = (substr ($this->duration,0,3) == '00:')?substr ($this->duration,3):$this->duration;
        $this->slug = Functions::CreateSlug($this->title);
        $this->date_created = date ('m/d/Y', strtotime ($this->date_created));
        Plugin::Trigger ('video.get');

    }



    /**
     * Check if a record exists matching the given criteria
     * @param array $data Key/Value pairs to use in select criteria i.e. array (field_name => value)
     * @return integer|boolean Returns record ID if record is found or boolean false if not found
     */
    static function Exist ($data) {

        $db = Database::GetInstance();
        $query = 'SELECT ' . self::$id_name . ' FROM ' . DB_PREFIX . self::$table . ' WHERE';

        foreach ($data as $key => $value) {
            $value = $db->Escape ($value);
            $query .= " $key = '$value' AND";
        }

        $query = substr ($query, 0, -4);
        $result = $db->Query ($query);

        if ($db->Count($result) > 0) {
            $row = $db->FetchAssoc ($result);
            return $row[self::$id_name];
        } else {
            return false;
        }

    }



    /**
     * Create a new record using the given criteria
     * @param array $data Key/Value pairs to use as data for new record i.e. array (field_name => value)
     * @return integer Returns the ID of the newly created record
     */
    static function Create ($data) {

        $db = Database::GetInstance();
        $query = 'INSERT INTO ' . DB_PREFIX . self::$table;
        $fields = 'date_created, ';
        $values = 'NOW(), ';

        Plugin::Trigger ('video.before_save');
        foreach ($data as $_key => $_value) {
            $fields .= "$_key, ";
            $values .= "'" . $db->Escape ($_value) . "', ";
        }

        $fields = substr ($fields, 0, -2);
        $values = substr ($values, 0, -2);
        $query .= " ($fields) VALUES ($values)";
        $db->Query ($query);
        Plugin::Trigger ('video.create');
        return $db->LastId();

    }



    /**
     * Update current record using the given data
     * @param array $data Key/Value pairs of data to be updated i.e. array (field_name => value)
     * @return void Record is updated in DB
     */
    public function Update ($data) {

        Plugin::Trigger ('video.before_update');
        $query = 'UPDATE ' . DB_PREFIX . self::$table . " SET";
        foreach ($data as $_key => $_value) {
            $query .= " $_key = '" . $this->db->Escape ($_value) . "',";
        }

        $query = substr ($query, 0, -1);
        $id_name = self::$id_name;
        $query .= " WHERE $id_name = " . $this->$id_name;
        $this->db->Query ($query);
        $this->Get ($this->$id_name);
        Plugin::Trigger ('video.update');

    }
    
    
    
    /**
     * Delete a video
     * @param integer $video_id ID of video to be deleted
     * @return void Video is deleted from database and all related files and records are also deleted
     */
    static function Delete ($video_id) {

        $db = Database::GetInstance();
        $video = new self ($video_id);
        Plugin::Trigger ('video.delete');

        // Delete files
        @unlink(UPLOAD_PATH . '/' . $video->filename . '.flv');
        @unlink(UPLOAD_PATH . '/thumbs/' . $video->filename . '.jpg');
        @unlink(UPLOAD_PATH . '/mp4/' . $video->filename . '.mp4');

        // Delete related records
        $query1 = "DELETE FROM " . DB_PREFIX . "comments WHERE video_id = $video_id";
        $query2 = "DELETE FROM " . DB_PREFIX . "ratings WHERE video_id = $video_id";
        $query3 = "DELETE FROM " . DB_PREFIX . "favorites WHERE video_id = $video_id";
        $query4 = "DELETE FROM " . DB_PREFIX . "flags WHERE type = 'video' and id = $video_id";
        $query5 = "DELETE FROM " . DB_PREFIX . "videos WHERE video_id = $video_id";
        $db->Query ($query1);
        $db->Query ($query2);
        $db->Query ($query3);
        $db->Query ($query4);
        $db->Query ($query5);

    }



    /**
     * Generate a unique random string for a video filename
     * @return string Random video filename
     */
    static function CreateFilename() {
        $db = Database::GetInstance();
        do {
            $filename = Functions::Random(20);
            if (!self::Exist (array ('filename' => $filename))) $filename_available = true;
        } while (empty ($filename_available));
        return $filename;
    }

}

?>