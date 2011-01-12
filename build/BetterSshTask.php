<?php

require_once 'phing/Task.php';

/**
 * Based on the base SSH task, provides some additional checks to ensure
 * a command executes successfully or not. 
 *
 * @author marcus@silverstripe.com.au
 * @license BSD License http://silverstripe.org/bsd-license/
 */


class BetterSshTask extends Task {

    private $host = "";
    private $port = 22;
    private $username = "";
    private $password = "";
    private $command = "";
    private $pubkeyfile = '';
    private $privkeyfile = '';
    private $privkeyfilepassphrase = '';
	private $ignoreerrors = false;

    public function setHost($host) 
    {
        $this->host = $host;
    }

    public function getHost() 
    {
        return $this->host;
    }

    public function setPort($port) 
    {
        $this->port = $port;
    }

    public function getPort() 
    {
        return $this->port;
    }

    public function setUsername($username) 
    {
        $this->username = $username;
    }

    public function getUsername() 
    {
        return $this->username;
    }

    public function setPassword($password) 
    {
        $this->password = $password;
    }

    public function getPassword() 
    {
        return $this->password;
    }

    /**
     * Sets the public key file of the user to scp
     */
    public function setPubkeyfile($pubkeyfile)
    {
        $this->pubkeyfile = $pubkeyfile;
    }

    /**
     * Returns the pubkeyfile
     */
    public function getPubkeyfile()
    {
        return $this->pubkeyfile;
    }
    
    /**
     * Sets the private key file of the user to scp
     */
    public function setPrivkeyfile($privkeyfile)
    {
        $this->privkeyfile = $privkeyfile;
    }

    /**
     * Returns the private keyfile
     */
    public function getPrivkeyfile()
    {
        return $this->privkeyfile;
    }
    
    /**
     * Sets the private key file passphrase of the user to scp
     */
    public function setPrivkeyfilepassphrase($privkeyfilepassphrase)
    {
        $this->privkeyfilepassphrase = $privkeyfilepassphrase;
    }

    /**
     * Returns the private keyfile passphrase
     */
    public function getPrivkeyfilepassphrase($privkeyfilepassphrase)
    {
        return $this->privkeyfilepassphrase;
    }
    
    public function setCommand($command) 
    {
        $this->command = $command;
    }

    public function getCommand() 
    {
        return $this->command;
    }
	
	public function setIgnoreErrors($ignore) {
		if (!is_bool($ignore)) {
			$ignore = $ignore == 'true' || $ignore == 1;
		}

		$this->ignoreerrors = $ignore;
	}
	
	public function getIgnoreErrors() {
		return $this->ignoreerrors;
	}


    public function init() 
    {
        if (!function_exists('ssh2_connect')) { 
            throw new BuildException("To use SshTask, you need to install the SSH extension.");
        }
        return TRUE;
    }

    public function main() 
    {
        $this->connection = ssh2_connect($this->host, $this->port);
        if (is_null($this->connection)) {
            throw new BuildException("Could not establish connection to " . $this->host . ":" . $this->port . "!");
        }

        $could_auth = null;
        if ( $this->pubkeyfile ) {
            $could_auth = ssh2_auth_pubkey_file($this->connection, $this->username, $this->pubkeyfile, $this->privkeyfile, $this->privkeyfilepassphrase);
        } else {
            $could_auth = ssh2_auth_password($this->connection, $this->username, $this->password);
        }

        if (!$could_auth) {
            throw new BuildException("Could not authenticate connection!");
        }
		
		$command = '('.$this->command.'  2>&1) && echo __COMPLETE';

        $stream = ssh2_exec($this->connection, $command);
        if (!$stream) {
            throw new BuildException("Could not execute command!");
        }

        stream_set_blocking( $stream, true );
		$data = '';
        while( $buf = fread($stream,4096) ){
            $data .= $buf;
        }

		if (strpos($data, '__COMPLETE') !== false || $this->ignoreerrors) {
			$data = str_replace('__COMPLETE', '', $data);
		} else {
			$this->log("Command field: $command", Project::MSG_WARN);
			throw new BuildException("Failed executing command : $data");
		}
		
		echo $data;
		
        fclose($stream);
    }
}