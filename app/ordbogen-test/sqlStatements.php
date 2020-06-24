<?php
interface statements {
    const testQuery = "SELECT * FROM users";
    const validateUser = "SELECT * FROM users WHERE email = ? AND password = ?";
    const getRequest = "SELECT * FROM api WHERE method = ? AND request = ?";
    const getUser = "SELECT * FROM users WHERE email = ?";
    const finishTodo = "UPDATE todo SET finished_at = UNIX_TIMESTAMP() WHERE id = ?";
    const createUser = "INSERT IGNORE INTO users (email,firstname,lastname,password) VALUES (?,?,?,?)";
    const createTodo = "INSERT INTO todo (user,todo) VALUES (?,?)";
    const getTodos = "SELECT td.id,CONCAT(usr.firstname,' ',usr.lastname) as user,td.todo,td.created_at,td.finished_at FROM todo td LEFT JOIN users usr ON td.user = usr.email";
}   
?>

