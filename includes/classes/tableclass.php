<?php
/***************************************************************************
 *                               tableclass.php
 *                            -------------------
 *   begin                : Tuesday, August 15, 2006
 *   copyright            : (C) 2006 Paden Clayton
 *   website              : http://www.fasttracksites.com
 *   email                : sales@fasttracksites.com
 *
 *
 ***************************************************************************/



class tableClass { 
	var $border = 0;  
	var $padding = 0;  
	var $spacing = 0;  
	var $class = "";  
	var $id = "";  
	var $style = "";  
	var $width = "";  
	var $tHeadRowData = array();
	var $tBodyRowData = array();
	var $tFootRowData = array();

	//===============================================================
	// This function will me used for setting our template variables 
	//===============================================================
	public function __construct($border = 0, $padding = 0, $spacing = 0, $class = "", $id = "", $extraOptions = array()) {
		$this->border = $border;
		$this->padding = $padding;
		$this->spacing = $spacing;
		$this->class = $class;
		$this->id = $id;
		$this->width = ( !empty( $extraOptions['width'] ) ) ? $extraOptions['width'] : "";
		$this->style = ( !empty( $extraOptions['style'] ) ) ? $extraOptions['style'] : "";
	}
	
	
	/**
	 * Generates a table column array based on an array of column names.
	 *
	 * This is utilzed for tables that are changed via filters, the filters return a plain array 
	 * that needs to be turned into a table style array for this class.
	 * 
	 * @access public
	 * @param mixed $columns
	 * @param string $type (default: 'th')
	 * @return void
	 */
	public function generateTableColumns( $columns, $type = 'th' ) {
		$tableColumns = array();
		
		if ( count( $columns ) > 0 ) {
			foreach ( $columns as $column_name => $column_display_name ) {
				$text = ( is_array( $column_display_name ) ) ? $column_display_namedata['text'] : $column_display_name;
		
				$tableColumns[] = array( 
					'type' => 'th', 
					'data' => $text
				);
			}
		}
		
		return $tableColumns;
	}
	
	//===============================================================
	// This function will add a new row to the table
	//===============================================================
	public function addNewRow($data, $id = "", $class = "", $section = "tbody", $extraOptions = array()) {
		$style = (!empty($extraOptions['style'])) ? $extraOptions['style'] : '';
		
		switch ($section) {
			case 'thead':
				$this->tHeadRowData[] = array("class" => $class, 'data' => $data, "id" => $id, "style" => $style);
				break;
			case 'tfoot':
				$this->tFootRowData[] = array("class" => $class, 'data' => $data, "id" => $id, "style" => $style);
				break;
			default:
				$this->tBodyRowData[] = array("class" => $class, 'data' => $data, "id" => $id, "style" => $style);
				break;
		}
	}
	
	//===============================================================
	// This function return the number of elements in a section
	//===============================================================
	public function numOfElements($section = "tbody") {
		switch ($section) {
			case 'thead':
				return count($this->tHeadRowData);
				break;
			case 'tfoot':
				return count($this->tFootRowData);
				break;
			default:
				return count($this->tBodyRowData);
				break;
		}
	}
	
	//===============================================================
	// This function will allow us to generate each sections HTML
	//===============================================================
	public function returnSectionHTML($sectionArray) {	
		$html = '';
		
		foreach ($sectionArray as $key => $rowChunk) {
			$classBit = (!empty($rowChunk['class'])) ? " class=\"" . $rowChunk['class'] . "\"" : "";
			$idBit = (!empty($rowChunk['id'])) ? " id=\"" . $rowChunk['id'] . "\"" : "";
			$styleBit = (!empty($rowChunk['style'])) ? " style=\"" . $rowChunk['style'] . "\"" : "";
			
			$html .= "
							<tr" . $idBit . $classBit . $styleBit . ">";
							
			if (is_array($rowChunk['data'])) {
				foreach ($rowChunk['data'] as $key => $rowChunkData) {
					$typeBit = (!empty($rowChunkData['type'])) ? $rowChunkData['type'] : "td";
					$classBit = (!empty($rowChunkData['class'])) ? " class=\"" . $rowChunkData['class'] . "\"" : "";
					$idBit = (!empty($rowChunkData['id'])) ? " id=\"" . $rowChunkData['id'] . "\"" : "";
					$colspanBit = (!empty($rowChunkData['colspan'])) ? " colspan=\"" . $rowChunkData['colspan'] . "\"" : "";
					$rowspanBit = (!empty($rowChunkData['rowspan'])) ? " rowspan=\"" . $rowChunkData['rowspan'] . "\"" : "";
					$styleBit = (!empty($rowChunkData['style'])) ? " style=\"" . $rowChunkData['style'] . "\"" : "";
					$widthBit = (!empty($rowChunkData['width'])) ? " width=\"" . $rowChunkData['width'] . "\"" : "";
					$bgcolorBit = (!empty($rowChunkData['bgcolor'])) ? " bgcolor=\"" . $rowChunkData['bgcolor'] . "\"" : "";
					$alignBit = (!empty($rowChunkData['align'])) ? " align=\"" . $rowChunkData['align'] . "\"" : "";
					
					$html .= "
									<" . $typeBit . $idBit . $classBit . $bgcolorBit . $alignBit . $widthBit . $colspanBit . $rowspanBit . $styleBit . ">" . $rowChunkData['data'] . "</" . $typeBit . ">";
				}
			}
			
			$html .= "
							</tr>";
		}
			
		return $html;
	}
	
	//===============================================================
	// This function will allow us to generate the tables HTML
	//===============================================================
	public function returnTableHTML() {
		$borderBit = (!empty($this->border)) ? ' class="' . $this->border . '"' : '';
		$paddingBit = (!empty($this->padding)) ? ' class="' . $this->padding . '"' : '';
		$spacingBit = (!empty($this->spacing)) ? ' class="' . $this->spacing . '"' : '';
		$classBit = (!empty($this->class)) ? ' class="' . $this->class . '"' : '';
		$idBit = (!empty($this->id)) ? ' id="' . $this->id . '"' : '';
		$styleBit = (!empty($this->style)) ? ' style="' . $this->style . '"' : '';
		$widthBit = (!empty($this->width)) ? ' width="' . $this->width . '"' : '';
	
		// Start our table
		$html = '
						<table' . $idBit . $borderBit . $paddingBit . $spacingBit . $classBit . $widthBit . $styleBit . '>';
		
		// Add our sections
		$html .= (count($this->tHeadRowData) > 0) ? "
							<thead>
								" . $this->returnSectionHTML($this->tHeadRowData) . "
							</thead>" : "";
		$html .= "
							<tbody>
								" . $this->returnSectionHTML($this->tBodyRowData) . "
							</tbody>";
		$html .= (count($this->tFootRowData) > 0) ? "
							<tfoot>
								" . $this->returnSectionHTML($this->tFootRowData) . "
							</tfoot>" : "";
		// Close the table
		$html .= "
						</table>";
		
		// Return the HTML
		return $html;
	}
}