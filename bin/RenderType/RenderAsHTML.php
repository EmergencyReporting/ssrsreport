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
  * class RenderAsHTML
  */
class RenderAsHTML extends RenderBaseType implements IRenderType
{
    /**     
     * @xml : BookmarkID
     */
    public $BookmarkID;

   /**    
    * @xml :DocMap
    */
    public $DocMap;

    /**     
     * @xml : ExpandContent
     */
    public $ExpandContent;

    /**     
     * @xml : FindString
     */
    public $FindString;

    /**     
     * @xml : GetImage
     */
    public $GetImage;

    /**     
     * @xml : HTMLFragment
     */
     public $HTMLFragment;

    /**     
     * @xml : JavaScript
     */
     public $JavaScript;

    /**     
     * @xml : LinkTarget
     */
    public $LinkTarget;

    /**     
     * @xml : OnlyVisibleStyles
     */
    public $OnlyVisibleStyles;

    /**     
     * @xml : Parameters
     */
    public $Parameters;

    /**
     *
     * @xml : ReplacementRoot
     */
    public $ReplacementRoot;
    
    /**     
     * @xml : Section
     */
    public $Section;

    /**     
     * @xml : StreamRoot
     */
    public $StreamRoot;

    /**     
     * @xml : StyleStream
     */
    public $StyleStream;

    /**     
     * @xml : Toolbar
     */
    public $Toolbar;

    /**     
     * @xml : Zoom
     */
    public $Zoom;

    public function RenderAsHTML()
    {
        $this->BookmarkID = null;
        $this->DocMap = null;
        $this->ExpandContent = null;
        $this->FindString = null;
        $this->GetImage = null;
        $this->HTMLFragment = null;
        $this->JavaScript = null;
        $this->LinkTarget = null;
        $this->OnlyVisibleStyles = null;
        $this->Parameters = null;
        $this->Section = null;
        $this->StreamRoot = null;
        $this->StyleStream = null;
        $this->Toolbar = null;
        $this->Zoom = null;
    }
    
    public function GetFormat()
    {
        return "HTML4.0";
    }

    public function GetDevInfoXML()
    {
        return parent::GetDevInfoXML_Base($this);
    }
}
?>
