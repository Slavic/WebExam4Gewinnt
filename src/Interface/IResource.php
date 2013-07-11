<?php

/**
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
interface IResource
{
    public function __construct();
    public function __destruct();
    public function exec($strQuery);
    public function read($strQuery, $blnArray = false);
    public function readSingle($strQuery);
}