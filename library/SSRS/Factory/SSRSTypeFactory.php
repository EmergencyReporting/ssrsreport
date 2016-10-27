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
  * class SSRSTypeFactory
  */
class SSRSTypeFactory
{
    /**
     *
     * @var array
     */
    private static $SSRSTypes = array();
    
    /**
     *
     * @var array
     */
    private static $SSRSEnums = array();

    /**
     * To register a SSRS type with factory
     * @param string $ssrsType
     */
    public static function RegsiterType($ssrsType)
    {
        $class = new ReflectionClass($ssrsType);
        if (! $class->implementsInterface('ISSRSBaseType'))
        {
            throw new PReportException("",
                "Only classes which implements 'ISSRSBaseType' " .
                "can be registered with SSRSTypeFactory");
        }       

        self::$SSRSTypes[$ssrsType] = $ssrsType; 
    }

    /**
     * To register an ssrs enum with factory
     * @param string $ssrsEnum
     */
    public static function RegisterEnum($ssrsEnum)
    {
        self::$SSRSEnums[$ssrsEnum] = $ssrsEnum;
    }

    public static function CreateSSRSObject($ssrsType, $stdObject)
    {
        if (!array_key_exists($ssrsType, self::$SSRSTypes))
        {
            throw new PReportException("",
                                       "Requested SSRS Type $ssrsType is not Registered!");
        }

        $class = new ReflectionClass($ssrsType);
        $object = $class->newInstance();
        $object->FromStdObject($stdObject);
        return $object;
    }

    /**
     * To get the generic type of a type (basic or ssrs)
     * @param string $type
     * @return string
     */
    public static function GetType($type)
    {
        $retType = 'unknown';

        if($type == 'bool' || $type == 'int' ||
           $type == 'DateTime' || $type == 'string' ||
           $type == 'double')
       {
            $retType = 'basic';
       }
       else if(array_key_exists($type, self::$SSRSTypes) ||
               array_key_exists($type, self::$SSRSEnums)
       )
       {
            $retType = 'ssrs';
       }
       return $retType;
    }
}
?>
