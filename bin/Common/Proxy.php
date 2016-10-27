<?php
/**
  *
  * Copyright (c) 2009, Persistent Systems Limited
  *
  * Redistribution and use, with or without modification, are permitted
  *  provided that the following  conditions are met:
  *   - Redistributions of source code must retain the above copyright notice,
  *     this list of conditions and the following disclaimer.
  *   - Neither the name of Persistent Systems Limited nor the names of its contributors
  *     may be used to endorse or promote products derived from this software
  *     without specific prior written permission.
  *
  * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
  * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO,
  * THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR
  * PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR
  * CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
  * EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO,
  * PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS;
  * OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY,
  * WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR
  * OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE,
  * EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
  */

/**
  *
  * class Proxy
  *
  * @copyright  2009 Persistent System Limited
  */
class Proxy
{
	private $_address;
	private $_port;
        private $_login;
        private $_password;

	/**
	 * @param string $address The Proxy Server Name
         * @param string  $port The Proxy Server Port Number
         * @param string  $login The Proxy Server login name
         * @param string  $password The Proxy Server Password
	 */
	public function Proxy($address, $port, $login = null, $password = null)
	{
            $this->_address = $address;
            $this->_port = $port;
            $this->_login = $login;
            $this->_password = $password;
	}      

        /**
         *
         * @return <string> Proxy host
         */
        public function getHost()
        {
            return $this->_address;
        }

        /**
         *
         * @return <string> Proxy Port
         */
        public function getPort()
        {
            return $this->_port;
        }

        /**
         *
         * @return <string> Porxy login name
         */
        public function getLogin()
        {
            return $this->_login;
        }

        /**
         *
         * @return <string> Proxy Password
         */
        public function getPassword()
        {
            return $this->_password;
        }

        /**
         *
         * @return <array> proxy as key value pair
         */
        public function getProxy()
        {
            $options = array();
            $options['proxy_host'] = $this->_address;
            $options['proxy_port'] =  intval($this->_port);
            if(isset($this->_login))
            {
                $options['proxy_login']  = $this->_login;
                $options['proxy_password']  = $this->_password;
            }
            return $options;
        }

        public function getBase64Auth()
        {
             return "Proxy-Authorization: Basic " .
                base64_encode($this->_login .
                              ':' .
                              $this->_password);
        }
};
?>