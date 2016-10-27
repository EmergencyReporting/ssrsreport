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
  * class RenderAsIMAGE
  */
class RenderAsIMAGE extends RenderBaseType implements IRenderType
{
    /**    
     * @xml : ColorDepth
     */
    public $ColorDepth;

    /**     
     * @xml : Columns
     */
    public $Columns;
    
    /**     
     * @xml : ColumnSpacing
     */
    public $ColumnSpacing;
    
    /**     
     * @xml : DpiX
     */
    public $DpiX;
    
    /**     
     * @xml : DpiY
     */
     public $DpiY;

     /**      
      * @xml : EndPage
      */
     public $EndPage;

     /**      
      * @xml : MarginBottom
      */
     public $MarginBottom;

     /**      
      * @xml : MarginLeft
      */
     public $MarginLeft;

     /**      
      * @xml : MarginRight
      */
     public $MarginRight;

     /**      
      * @xml : MarginTop
      */
     public $MarginTop;

     /**      
      * @xml : OutputFormat
      */
     public $OutputFormat;

     /**      
      * @xml : PageHeight
      */
     public $PageHeight;

     /**      
      * @xml : PageWidth
      */
     public $PageWidth;

     /**      
      * @xml : StartPage
      */
     public $StartPage;

     public function RenderAsIMAGE()
     {
        $this->ColorDepth  = null;
        $this->Columns = null;
        $this->ColumnSpacing = null;
        $this->DpiX = null;
        $this->DpiY = null;
        $this->EndPage = null;
        $this->MarginBottom = null;
        $this->MarginLeft = null;
        $this->MarginRight = null;
        $this->MarginTop = null;
        $this->OutputFormat = null;
        $this->PageHeight = null;
        $this->PageWidth = null;
        $this->StartPage = null;
     }

     public function GetFormat()
     {
        return "IMAGE";
     }

     public function GetDevInfoXML()
     {
        return parent::GetDevInfoXML_Base($this);
     }
}

?>
