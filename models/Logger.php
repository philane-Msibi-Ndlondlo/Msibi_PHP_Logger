<?php

/*
 *  @desc Contains Logger Implementations
 *  @author Philane Msibi
 *  @version 1.0
 */

namespace Msibi_PHP;

class Logger {

    /*
     *  @desc Store the log file name
     *  @var $filename
     *  @version 1.0
     */

    private static $filename;

    /*
     *  @desc Store the log folder name
     *  @var $log_folder
     *  @version 1.0
     */

    private static $log_folder;
    
    /*
     *  @desc Store the current timestamp of a log
     *  @var $curr_time
     *  @version 1.0
     */

    private static $curr_time;

    /*
     *  @desc Store the log file full path
     *  @var $filepath
     *  @version 1.0
     */

    
    private static $filepath;
    
    /*
     *  @desc Store the new content of a log
     *  @var $content
     *  @version 1.0
     */

    private static $content;

    /*
     *  @desc Initialize the Logger properties
     *  @func init()
     *  @param $filename - File name of the log file
     *  @param $log_folder - Folder of the log file
     *  @return void
     *  @version 1.0
     */

     public static function init($filename = '', $log_folder = '') {
         
        self::$curr_time = date('d-M-Y');
        self::$filename = ($filename !== null && !empty($filename)) ? $filename : 'log_'.self::$curr_time;
        self::$log_folder = ($log_folder !== null && !empty($log_folder)) ? $log_folder : 'logs';
        //self::$filepath =  dirname(__FILE__, 2).DIRECTORY_SEPARATOR.self::$log_folder;
        self::$filepath =  self::$log_folder;
        self::$content = '';

        if (!FileManager::is_directory_exist(self::$filepath)) {
            if (!FileManager::create_directory(self::$filepath, 0777)) die("ERROR: Could not mkdir {self::$filepath}");
        }

        if (!FileManager::is_file_exist(self::$filename)) {
           
            self::$filepath = FileManager::append_to_path(self::$filepath, self::$filename.".txt");
            
            FileManager::create_file(self::$filepath); 
        }
     }

    /*
     *  @desc Create a log
     *  @func log()
     *  @param $message - Message to be logged to file
     *  @return void
     *  @version 1.0
     */

    public static function Log(string $message) {
        
        if (!FileManager::is_file_exist(self::$filepath) || !FileManager::is_accessible(self::$filepath)) self::init();

        if (empty($message)) return;

        self::write($message, LogType::LOG); 
        
    }

    /*
     *  @desc write a log to file
     *  @func write()
     *  @param $message - Message to be logged to file
     *  @return void
     *  @version 1.0
     */

    private static function write(string $message, $flag = LogType::LOG) {

        try {

            $file_handler = FileManager::open_file(self::$filepath, FileModeType::APPEND);
            if ($file_handler == null) echo "NULL";

            self::$curr_time = date('d-M-Y H:i:s A');

            self::$content = "[".self::$curr_time."] ".LogType::getName($flag)." : ". $message.PHP_EOL;
            
            fwrite($file_handler, self::$content);

            FileManager::save_file($file_handler);

        } catch(Exception $ex) {
            echo $ex->getMessage();
        }
    
    }

    /*
     *  @desc Create an error
     *  @func Error()
     *  @param $message - Message/error to be logged to file
     *  @return void
     *  @version 1.0
     */

    public static function Error(string $message) {
        
        if (!FileManager::is_file_exist(self::$filepath) || !FileManager::is_accessible(self::$filepath)) self::init();

        if (empty($message)) return;

        self::write($message, LogType::ERROR); 
       
    }
}
