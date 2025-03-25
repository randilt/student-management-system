<?php
class Stream {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Get all streams
    public function getStreams() {
        $this->db->query('SELECT * FROM streams');
        
        $results = $this->db->resultSet();

        return $results;
    }

    // Get stream by ID
    public function getStreamById($id) {
        $this->db->query('SELECT * FROM streams WHERE id = :id');
        $this->db->bind(':id', $id);

        $row = $this->db->single();

        return $row;
    }

    // Get subjects by stream ID
    public function getSubjectsByStreamId($streamId) {
        $this->db->query('SELECT * FROM subjects WHERE stream_id = :stream_id');
        $this->db->bind(':stream_id', $streamId);

        $results = $this->db->resultSet();

        return $results;
    }

    // Get stream head
    public function getStreamHead($streamId) {
        $this->db->query('SELECT users.* FROM users 
                        JOIN streams ON users.id = streams.head_user_id 
                        WHERE streams.id = :stream_id');
        $this->db->bind(':stream_id', $streamId);

        $row = $this->db->single();

        return $row;
    }
}

