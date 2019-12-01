<?php
/***************************************************************************
 *                               Table.php
 *                            -------------------
 *   begin                : Tuesday, August 15, 2006
 *   copyright            : (C) 2006 Paden Clayton
 *
 *
 ***************************************************************************/


class Table {
	var $border = 0;
	var $padding = 0;
	var $spacing = 0;
	var $class = '';
	var $id = '';
	var $style = '';
	var $width = '';
	var $tHeadRowData = [];
	var $tBodyRowData = [];
	var $tFootRowData = [];

	/**
	 * Table constructor.
	 *
	 * @param int    $border
	 * @param int    $padding
	 * @param int    $spacing
	 * @param string $class
	 * @param string $id
	 * @param array  $extraOptions
	 */
	public function __construct( $border = 0, $padding = 0, $spacing = 0, $class = '', $id = '', $extraOptions = [] ) {
		$this->border  = $border;
		$this->padding = $padding;
		$this->spacing = $spacing;
		$this->class   = $class;
		$this->id      = $id;
		$this->width   = ( ! empty( $extraOptions['width'] ) ) ? $extraOptions['width'] : '';
		$this->style   = ( ! empty( $extraOptions['style'] ) ) ? $extraOptions['style'] : '';
	}


	/**
	 * Generates a table column array based on an array of column names.
	 *
	 * This is utilized for tables that are changed via filters, the filters return a plain array
	 * that needs to be turned into a table style array for this class.
	 *
	 * @access public
	 *
	 * @param array  $columns
	 * @param string $type (default: 'th')
	 *
	 * @return array
	 */
	public function generateTableColumns( array $columns, $type = 'th' ): array {
		$tableColumns = [];

		if ( count( $columns ) > 0 ) {
			foreach ( $columns as $column_name => $column_display_name ) {
				$text = ( is_array( $column_display_name ) ) ? $column_display_name['text'] : $column_display_name;

				$tableColumns[] = [
					'type' => $type,
					'data' => $text,
				];
			}
		}

		return $tableColumns;
	}

	/**
	 * Add a new row to the table.
	 *
	 * @param        $data
	 * @param string $id
	 * @param string $class
	 * @param string $section
	 * @param array  $extraOptions
	 */
	public function addNewRow( $data, $id = '', $class = '', $section = "tbody", $extraOptions = [] ) {
		$style = ( ! empty( $extraOptions['style'] ) ) ? $extraOptions['style'] : '';

		switch ( $section ) {
			case 'thead':
				$this->tHeadRowData[] = [ "class" => $class, 'data' => $data, "id" => $id, "style" => $style ];
				break;
			case 'tfoot':
				$this->tFootRowData[] = [ "class" => $class, 'data' => $data, "id" => $id, "style" => $style ];
				break;
			default:
				$this->tBodyRowData[] = [ "class" => $class, 'data' => $data, "id" => $id, "style" => $style ];
				break;
		}
	}

	/**
	 * Return the number of elements in a section.
	 *
	 * @param string $section
	 *
	 * @return int
	 */
	public function numOfElements( $section = "tbody" ): int {
		switch ( $section ) {
			case 'thead':
				return count( $this->tHeadRowData );
				break;
			case 'tfoot':
				return count( $this->tFootRowData );
				break;
			default:
				return count( $this->tBodyRowData );
				break;
		}
	}

	/**
	 * Generate the table's HTML.
	 *
	 * @return string
	 */
	public function returnTableHTML(): string {
		$borderBit  = ( ! empty( $this->border ) ) ? ' class="' . $this->border . '"' : '';
		$paddingBit = ( ! empty( $this->padding ) ) ? ' class="' . $this->padding . '"' : '';
		$spacingBit = ( ! empty( $this->spacing ) ) ? ' class="' . $this->spacing . '"' : '';
		$classBit   = ( ! empty( $this->class ) ) ? ' class="' . $this->class . '"' : '';
		$idBit      = ( ! empty( $this->id ) ) ? ' id="' . $this->id . '"' : '';
		$styleBit   = ( ! empty( $this->style ) ) ? ' style="' . $this->style . '"' : '';
		$widthBit   = ( ! empty( $this->width ) ) ? ' width="' . $this->width . '"' : '';

		// Start our table
		$html = '
						<table' . $idBit . $borderBit . $paddingBit . $spacingBit . $classBit . $widthBit . $styleBit . '>';

		// Add our sections
		$html .= ( count( $this->tHeadRowData ) > 0 ) ? "
							<thead>
								" . $this->returnSectionHTML( $this->tHeadRowData ) . "
							</thead>" : '';

		$html .= "
							<tbody>
								" . $this->returnSectionHTML( $this->tBodyRowData ) . "
							</tbody>";

		$html .= ( count( $this->tFootRowData ) > 0 ) ? "
							<tfoot>
								" . $this->returnSectionHTML( $this->tFootRowData ) . "
							</tfoot>" : '';
		// Close the table
		$html .= "
						</table>";

		// Return the HTML
		return $html;
	}

	/**
	 * Generate a section's HTML.
	 *
	 * @param array $sectionArray
	 *
	 * @return string
	 */
	public function returnSectionHTML( array $sectionArray ): string {
		$html = '';

		foreach ( $sectionArray as $rowChunk ) {
			$classBit = ( ! empty( $rowChunk['class'] ) ) ? " class=\"" . $rowChunk['class'] . "\"" : '';
			$idBit    = ( ! empty( $rowChunk['id'] ) ) ? " id=\"" . $rowChunk['id'] . "\"" : '';
			$styleBit = ( ! empty( $rowChunk['style'] ) ) ? " style=\"" . $rowChunk['style'] . "\"" : '';

			$html .= "
							<tr" . $idBit . $classBit . $styleBit . ">";

			if ( is_array( $rowChunk['data'] ) ) {
				foreach ( $rowChunk['data'] as $rowChunkData ) {
					$alignBit   = ( ! empty( $rowChunkData['align'] ) ) ? " align=\"" . $rowChunkData['align'] . "\"" : '';
					$bgColorBit = ( ! empty( $rowChunkData['bgcolor'] ) ) ? " bgcolor=\"" . $rowChunkData['bgcolor'] . "\"" : '';
					$classBit   = ( ! empty( $rowChunkData['class'] ) ) ? " class=\"" . $rowChunkData['class'] . "\"" : '';
					$colspanBit = ( ! empty( $rowChunkData['colspan'] ) ) ? " colspan=\"" . $rowChunkData['colspan'] . "\"" : '';
					$idBit      = ( ! empty( $rowChunkData['id'] ) ) ? " id=\"" . $rowChunkData['id'] . "\"" : '';
					$rowspanBit = ( ! empty( $rowChunkData['rowspan'] ) ) ? " rowspan=\"" . $rowChunkData['rowspan'] . "\"" : '';
					$styleBit   = ( ! empty( $rowChunkData['style'] ) ) ? " style=\"" . $rowChunkData['style'] . "\"" : '';
					$typeBit    = ( ! empty( $rowChunkData['type'] ) ) ? $rowChunkData['type'] : "td";
					$widthBit   = ( ! empty( $rowChunkData['width'] ) ) ? " width=\"" . $rowChunkData['width'] . "\"" : '';

					$html .= "
									<" . $typeBit . $idBit . $classBit . $bgColorBit . $alignBit . $widthBit . $colspanBit . $rowspanBit . $styleBit . ">" . $rowChunkData['data'] . "</" . $typeBit . ">";
				}
			}

			$html .= "
							</tr>";
		}

		return $html;
	}
}