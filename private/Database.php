<?php

namespace DaveComputerGeek;

class Database {
    
    private $PDO;
    
    public function __construct(String $host, String $user, String $pass, String $name) {
        // Establish a Database Connection.
        
        try {
            // Try to establish a database connection using the credentials provided.
            $this->pdo = new \PDO("mysql:host=" + $host + ";dbname=" + $name, $user, $pass);
        } catch(\Exception $ex) {
            // For some reason the connection was not established, log it.
            error_log("Database Connection Issue!");
        }
    }
    
    /**
     * Performs a select database query using the fields, table, and where values provided, with where being optional.
     * @param array $fields
     * @param String $table
     * @param array $where
     * @return array
     */
    public function select(array $fields, String $table, array $where = []) {
        // Variable to hold the string of fields for our query.
        $query_fields = "";
        
        // Loop through all fields and add each one to the query fields string, separated by a comma and space.
        for ($f = 0; $f < count($fields); $f++) {
            $query_fields += $fields[$f];
            
            // Detects if multiple fields and currently not on last field, then adds comma and space to string.
            if($f < count($fields)) {
                $query_fields += ", ";
            }
        }
        
        // Check if a where clause is needed.
        if(!empty($where)) {
            // Keep track of the current where field.
            $w = 0;
            // The where string for our query string.
            $where_query = "";
            // The where array for execution.
            $where_execute = [];
            
            // Loop through each where field.
            foreach ($where as $where_name => $where_value) {
                // Add the field to the where string with a tag based on the field name.
                $where_query .= $where_name . " = :" . $where_name;
                // Add the field to the execution string based on the above tag.
                $where_execute[':' . $where_name] = $where_value;
                
                // If multiple where fields and not currently on last field, separate with a space the word "AND" followed by another space.
                if($w < count($where)) $where_query .= " AND ";
                
                // Increment our tracker by one.
                $w++;
            }
            
            // Prepare the query string for execution with where clause.
            $query = $this->PDO->prepare("SELECT " . $query_fields . " FROM " . $table . " WHERE " . $where_query);
            // Execure the query with the execution array.
            $query = $query->execute($where_execute);
        } else {
            // No where clause needed.
            
            // Prepare the query string for execution without the where clause.
            $query = $this->PDO->prepare("SELECT " . $query_fields . " FROM " . $table);
            // Execute the query.
            $query = $query->execute();
        }
        
        // Check if query was performed successfully.
        if($query) return $query->fetchAll(\PDO::FETCH_ASSOC);
        // Query was not successful. Log it and return empty array.
        error_log("Database Select Query did not execute successfully.");
        return [];
    }
    
}