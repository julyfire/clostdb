<?php

class MySSH{
	
	private $connection;
	private $sftp;
	
	public function __construct($host,$port=22,$username,$password){
		$this->connection = @ssh2_connect($host, $port);
		if (! $this->connection)
			throw new Exception("Could not connect to $host on port $port.");
		if (! @ssh2_auth_password($this->connection, $username, $password))
			throw new Exception("Could not authenticate with username $username " .
                                "and password $password.");
			
		$this->sftp = @ssh2_sftp($this->connection);
		if (! $this->sftp)
			throw new Exception("Could not initialize SFTP subsystem.");
	}
	
	public function uploadFile($local_file, $remote_file){
		$data_to_send = @file_get_contents($local_file);
		if ($data_to_send === false)
			throw new Exception("Could not open local file: $local_file.");
		$this->uploadData($data_to_send,$remote_file);
	}
	public function uploadData($data_to_send, $remote_file){
		$sftp = $this->sftp;
		$stream = @fopen("ssh2.sftp://$sftp$remote_file", 'w');

		if (!$stream)
			throw new Exception("Could not open file: $remote_file");

		if (@fwrite($stream, $data_to_send) === false)
			throw new Exception("Could not send data from file: $local_file.");

		@fclose($stream);
	}
	 public function receiveFile($remote_file, $local_file){
        $sftp = $this->sftp;
        $stream = @fopen("ssh2.sftp://$sftp$remote_file", 'r');
        if (! $stream)
            throw new Exception("Could not open file: $remote_file");
        $size = $this->getFileSize($remote_file);           
        $contents = '';
        $read = 0;
        $len = $size;
        while ($read < $len && ($buf = fread($stream, $len - $read))) {
          $read += strlen($buf);
          $contents .= $buf;
        }       
        file_put_contents ($local_file, $contents);
        @fclose($stream);
    }

    public function getFileSize($file){
      $sftp = $this->sftp;
        return filesize("ssh2.sftp://$sftp$file");
    }
	
	public function deleteFile($remote_file){
		$sftp = $this->sftp;
		unlink("ssh2.sftp://$sftp$remote_file");
	}
	public function exeCommand($command){
		$endSignal="__COMMAND_FINISHED__";
		$stream = ssh2_exec($this->connection, "$command;echo $endSignal" );
		
		if(!$stream ){
			throw new Exception( "fail to execute command [ $command ].");
		}
		else{
			stream_set_blocking( $stream, true );
			
			$data = "";
			$time_start = time();
			while( true){
				$data .= fread($stream,4096);
				//check if command has finished
				if(strpos($data,$endSignal) !== false) break;
				//if waiting more than 10s, then force quit
				if( (time()-$time_start) > 10 ){
					throw new Exception( "fail: timeout of 10 seconds has been reached" );
					break;
				}
			}
			@fclose($stream);
		}
		
		
		return $data;
	}
	public function exeCommand2($command){
		$output='';
		$command="echo '[START]';".$command.";echo '[END]'";
		echo $command."<br>";
		$shell=ssh2_shell($this->connection,"bash");
		
		fwrite($shell,$command."\n");
		
		$start=false;
		$start_time=time();
		$max_time=10;
		while((time()-$start_time)<$max_time){
			$line=fgets($shell);
			if(!strstr($line,$command)){
				if(preg_match("/\[START\]/",$line))
					$start=true;
				elseif(preg_match("/\[END\]/",$line))
					return $output;
				elseif($start)
					$output.=$line;
			}
		}
		if( (time()-$start_time) > $max_time )
			throw new Exception( "fail: timeout of 10 seconds has been reached" );
					
	}
}
?>
