<?php

/**
 * Description of Resource_DB_MySQL
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
class Resource_DB_MySQL implements IResource
{
    private $_resDb;

    /**
     *
     * @throws Resource_Exception
     */
    public function __construct() {
        $this->_resDb = mysql_connect(RESOURCE_DB_HOST . ":" . RESOURCE_DB_PORT, RESOURCE_DB_USER, RESOURCE_DB_PASSWORD);

        if($this->_resDb === false) {
            throw new Resource_Exception("Can't connect to host '" . RESOURCE_DB_HOST . ":" . RESOURCE_DB_PORT . "': " . mysql_error());
        }

        if(!mysql_selectdb(RESOURCE_DB_NAME, $this->_resDb)) {
            throw new Resource_Exception("Can't select database '" . RESOURCE_DB_NAME . "': " . mysql_error());
        }
    }

    /**
     *
     */
    public function __destruct() {
        mysql_close($this->_resDb);
    }

    /**
     *
     * @param String $strQuery
     * @return boolean
     * @throws Resource_Exception
     */
    public function exec($strQuery) {
        if (mysql_query($strQuery, $this->_resDb)) {
            return true;
        } else {
            throw new Resource_Exception("MySQL Query execute error: " . mysql_error() . " with SQL: $strQuery");
        }
    }

    /**
     *
     * @param String $strQuery
     * @param boolean $blnArray
     * @return resource|Array
     * @throws Resource_Exception
     */
    public function read($strQuery, $blnArray = false) {
        $objResultset = mysql_query($strQuery, $this->_resDb);

        if($objResultset === false) {
            throw new Resource_Exception("MySQL Query execute error: " . mysql_error() . " with SQL: $strQuery");
        }

        if($blnArray) {
            return $this->_toArray($objResultset);
        }

        return $objResultset;
    }

    /**
     *
     * @param String $strQuery
     * @return Array
     */
    public function readSingle($strQuery) {
        return array_shift($this->read($strQuery, true));
    }

    private function __clone() {
        ;
    }

    /**
     *
     * @param resource $objResultset
     * @return Array
     * @throws Resource_Exception
     */
    private function _toArray($objResultset) {
        $arrRows = mysql_fetch_assoc($objResultset);

        if($arrRows === false) {
            throw new Resource_Exception("MySQL fetch assoc error: " . mysql_error());
        }

        return $arrRows;
    }
}