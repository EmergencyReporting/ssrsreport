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
  * class SSRSBaseType
  */
abstract class SSRSBaseType
{
    /**
     *
     * @param string $type
     * @param StdObject $stdObject
     * @return ssrs Object
     */
     protected function FromStdObject_Base($type, $stdObject)
     {
        $object;

        if ($type != 'self')
        {
            $class = new ReflectionClass($type);
            $object = $class->newInstance();
        }
        else
        {
            $type = get_class($this);
            $class = new ReflectionClass($type);
            $object = $this;
        }

        $properties = $class->getProperties();
        foreach ($properties as $rawProperty)
        {
            $propertyName = $rawProperty->name;
            $property = new ReflectionProperty($object, $propertyName);
            $attributes = Utility::getAttributes($property);
            $value = null;

            switch (SSRSTypeFactory::GetType($attributes['type']))
            {
                case 'basic':
                    $value = self::EnumerateBasicObject($stdObject,
                                                        $propertyName,
                                                        $attributes);
                    break;

                case 'ssrs':
                    $value = self::EnumerateSSRSObject($stdObject,
                                                       $propertyName,
                                                       $attributes);
                    break;

                case 'unknown':
                    throw new PReportException("",
                                                  "Exception: Unknown_Type:"
                                                  . $attributes['type']);
                    break;
            }
            $property->setValue($object, $value);
        }
        $object->Initialize();
        return $object;
    }

    /**
     *
     * @param StdObject $stdObject
     * @param string $propertyName
     * @param array $attributes
     * @return basic type or collection of basic types
     */
    protected function EnumerateBasicObject($stdObject, $propertyName, $attributes)
    {
         $objectAsArray = (array)$stdObject;
         if ($attributes['IsCollection'] == true)
         {
            $result = array();
            if (array_key_exists($propertyName, $objectAsArray))
            {
                $collection = $objectAsArray[$propertyName];
                if (!is_array($collection))
                {
                    $collection = array($collection);
                }
                foreach ($collection as $item)
                {
                    $result[] = $item;
                }
            }
            return $result;
        }
        else
        {
            $value = null;
            if (array_key_exists($propertyName, $objectAsArray))
            {
               $value = $objectAsArray[$propertyName];
            }
            return $value;
        }
    }

    /**
     *
     * @param StdObject $stdObject
     * @param string $propertyName
     * @param array $attributes
     * @return ssrs type or collection of ssrs types
     */
    protected function EnumerateSSRSObject($stdObject, $propertyName, $attributes)
    {
         $objectAsArray = (array)$stdObject;
         if ($attributes['IsCollection'] == true)
         {
            $result = array();
            if (array_key_exists($propertyName, $objectAsArray))
            {
                $collectionAsObject = $objectAsArray[$propertyName];
                $collectionAsObjectAsArray = get_object_vars($collectionAsObject);
                if (array_key_exists($attributes['type'], $collectionAsObjectAsArray))
                {
                    $collection = $collectionAsObjectAsArray[$attributes['type']];
                    if (!is_array($collection))
                    {
                        $collection = array($collection);
                    }
                    foreach ($collection as $item)
                    {
                        $result[] = self::FromStdObject_Base($attributes['type'], $item);
                    }
                }
            }
            return $result;
        }
        else
        {
            $childObject = array();
            if (array_key_exists($propertyName, $objectAsArray))
            {
                if (array_key_exists('IsEnum', $attributes) &&
                    $attributes['IsEnum'])
                {
                    $childObject = $objectAsArray[$propertyName];
                }
                else
                {
                    $childObject = self::FromStdObject_Base($attributes['type'],
                                                       $objectAsArray[$propertyName]);
                }
            }
            return $childObject;
        }
    }
}
?>
