<?php

/**
 * 
 * @package smsApi
 * @author Hassel
 * @copyright 2013
 * @version 0.01
 * @access public
 * 
 *
 * <code>
 * <?php
 * require_once 'smsApi.php';
 *
 * $objekt = new smsApi();
 * $objekt->setNumber('004925151515');
 * $objekt->setText('Lorem Ipsum');
 * $objekt->send();
 * 
 * 
 * 
 * print_R($sms->getErrors());
 * print_R($sms->response);
 * ?>
 * </code>
 * 
 * 
 * 
 * 
 */

class smsApi
{
    var $url = 'http://192.168.1.1/api/sms/send-sms';
    var $number = '';
    var $text = '';
    var $date = '';
    var $rawxml = '';
    var $counterText = '';
    var $errors = array();
    var $response = '';


  
    /**
     * smsApi::__construct()
     * 
     * @return
     */
    function __construct()
    {
    }


  
    /**
     * smsApi::setNumber()
     * 
     * @param string $nr
     * @return
     */
    function setNumber($nr = '')
    {
        $this->number = $nr;
    }


    /**
     * smsApi::setText()
     * 
     * @param string $text
     * @return
     */
    function setText($text = '')
    {
        $this->text = trim($text);
        $this->counterText = strlen($this->text);
    }


    /**
     * smsApi::setDate()
     * 
     * @param string $date
     * @return
     */
    function setDate($date = '')
    {

        $this->date = $date;

    }


 
    /**
     * smsApi::getDate()
     * 
     * @param string $date
     * @return
     */
    function getDate($date = '')
    {

        if (!$date)
        {

            $this->date = date("Y-m-d H:i:s");

        }
        return $this->date;
    }


 
   /**
    * smsApi::createXml()
    * 
    * @return
    */
   private function createXml()
    {

        if ($this->number && $this->text)
        {
            $this->rawxml = '<?xml version  "1.0" encoding="UTF-8"?>
                    <request>
                    <Index>-1</Index>
                    <Phones><Phone>' . $this->number . '</Phone></Phones>
                    <Sca></Sca>
                    <Content>' . $this->text . '</Content>
                    <Length>' . $this->counterText . '</Length>
                    <Reserved>1</Reserved>
                    <Date>' . $this->getDate() . '</Date>
                    </request>';

            return $this->rawxml;
        } else
        {
            $this->setError('es fehlen Angaben');
            return false;
        }
    }


 
    /**
     * smsApi::_send()
     * 
     * @return
     */
    private function _send()
    {

 
        $ch = curl_init($this->url);
        #	curl_setopt($ch, CURLOPT_MUTE, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->createXml());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);

      
        return simplexml_load_string($output);


    }



    /**
     * smsApi::send()
     * 
     * @return
     */
    function send()
    {
        $this->createXml();
        
        if (empty($this->errors))
        {
            $this->response = $this->_send();
            return true;
            
        } else
        {
            return false;
        }
    }


    /**
     * smsApi::setError()
     * 
     * @param string $em
     * @return
     */
    function setError($em = '')
    {

        if ($em)
        {
            array_push($this->errors, $em);
        }
    }


    /**
     * smsApi::getErrors()
     * 
     * @return
     */
    function getErrors()
    {
        return $this->errors;
    }


}

?>
