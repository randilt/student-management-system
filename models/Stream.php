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

    // Find stream by name
    public function findStreamByName($name) {
        $this->db->query('SELECT * FROM streams WHERE name = :name');
        $this->db->bind(':name', $name);

        $row = $this->db->single();

        // Check row
        if($this->db->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Find subject by name and stream
    public function findSubjectByNameAndStream($name, $streamId) {
        $this->db->query('SELECT * FROM subjects WHERE name = :name AND stream_id = :stream_id');
        $this->db->bind(':name', $name);
        $this->db->bind(':stream_id', $streamId);

        $row = $this->db->single();

        // Check row
        if($this->db->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Add new stream
    public function addStream($data) {
        $this->db->query('INSERT INTO streams (name, description) VALUES (:name, :description)');
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        
        // Execute
        if($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Add new subject
    public function addSubject($data) {
        $this->db->query('INSERT INTO subjects (name, stream_id) VALUES (:name, :stream_id)');
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':stream_id', $data['stream_id']);
        
        // Execute
        if($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Get all streams with subjects
    public function getStreamsWithSubjects() {
        $streams = $this->getStreams();
        
        foreach($streams as $stream) {
            $stream->subjects = $this->getSubjectsByStreamId($stream->id);
            
            // Get stream head if exists
            if($stream->head_user_id) {
                $this->db->query('SELECT username, email FROM users WHERE id = :id');
                $this->db->bind(':id', $stream->head_user_id);
                $stream->head = $this->db->single();
            } else {
                $stream->head = null;
            }
        }
        
        return $streams;
    }
}

