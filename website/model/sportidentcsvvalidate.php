<?php
/** ----------------------------------------------------------------
 *  Strips unused columns form SportIdent CSV files
 *  This is required to ensure that address information is not 
 *  publiched via Splitsbrowser
 */

class SportIdentCVSValidator {
    var $NAME_INDEX;
    var $START_TIME_INDEX;
    var $TOTAL_TIME_INDEX;
    var $CLUB_INDEX;
    var $CLASS_INDEX;
    var $COURSE_INDEX;
    var $DISTANCE_INDEX;
    var $CLIMB_INDEX;
    var $NUM_CONTROLS_INDEX;
    var $START_PUNCH_INDEX;
    var $FIRST_SPLIT_INDEX;
    var $FIRSTNAME_INDEX;

    /**
     * Constructor for the CSVEventLoader object
     */
    function __construct() {
    }

    /**
     *  Strips unused fields from 
     */
    function stripUnusedFields($fileName, $outputfile) {

        // Open file    
        $handle = fopen($fileName, "r");
        if ($handle == false) {
            return (false);
        }

        $outputhandle = fopen($outputfile, "w");
        if ($outputhandle == false) {
            fclose($handle);
            return (false);
        }

        // Read header line and get columns to use
        $line = fgets($handle, 2048);
        if ($line == false) {
            fclose($handle);
            fclose($outputfile);
            return (false);
        }
        fwrite($outputhandle, $line);

        $delimiter = $this->setDelimiter($line);

        $isOE2002OrLater = $this->setFileFormat($line);
        $this->setColumnsIndices($isOE2002OrLater);

        // Read each line and write valid columns
        while (($tokens = fgetcsv($handle, 2000, $delimiter)) != FALSE) {

            // Create copy array
            $numcols = count($tokens);

            // skip line with single element - treated as blank )
            if ($numcols == 1) {
                continue;
            }

            // Validate that sufficient data exisit for at least 1 control
            if ($numcols-1 < $this->FIRST_SPLIT_INDEX) {
                fclose($handle);
                fclose($outputfile);
                return(false);
            }
            
            // Fill output array
            $output = array ($numcols);
            for ($index = 0; $index < $numcols; $index++) {
            	$output[$index] = " "; 
            }

            // Copy data across 
            $output[$this->NAME_INDEX] = $tokens[$this->NAME_INDEX];
            $output[$this->START_TIME_INDEX] = $tokens[$this->START_TIME_INDEX];
            $output[$this->TOTAL_TIME_INDEX] = $tokens[$this->TOTAL_TIME_INDEX];
            $output[$this->CLUB_INDEX] = $tokens[$this->CLUB_INDEX];
            $output[$this->CLASS_INDEX] = $tokens[$this->CLASS_INDEX];
            $output[$this->COURSE_INDEX] = $tokens[$this->COURSE_INDEX];
            $output[$this->DISTANCE_INDEX] = $tokens[$this->DISTANCE_INDEX];
            $output[$this->CLIMB_INDEX] = $tokens[$this->CLIMB_INDEX];
            $output[$this->NUM_CONTROLS_INDEX] = $tokens[$this->NUM_CONTROLS_INDEX];
            $output[$this->START_PUNCH_INDEX] = $tokens[$this->START_PUNCH_INDEX];
            $output[$this->FIRST_SPLIT_INDEX] = $tokens[$this->FIRST_SPLIT_INDEX];
            if ($isOE2002OrLater) {
                $output[$this->FIRSTNAME_INDEX] = $tokens[$this->FIRSTNAME_INDEX];
            }

            // Copy the splits
            for ($i = $this->NUM_CONTROLS_INDEX; $i < $numcols; $i ++) {
                $output[$i] = $tokens[$i];
            }

            // Write data
            $comma_separated = implode($delimiter, $output);
            // echo( $comma_separated ."\n" ); 
            fwrite($outputhandle, $comma_separated);
            fwrite($outputhandle, "\n");

        }

        fclose($handle);
        fclose($outputhandle);
        
         return(true);
    }

    /**
     * Sets the column indicies to use based on the file format
     */
    function setColumnsIndices($isOE2002OrLater) {
        if ($isOE2002OrLater) {
            $this->NAME_INDEX = 3;
            $this->START_TIME_INDEX = 9;
            $this->TOTAL_TIME_INDEX = 11;
            $this->CLUB_INDEX = 15;
            $this->CLASS_INDEX = 18;
            $this->COURSE_INDEX = 39;
            $this->DISTANCE_INDEX = 40;
            $this->CLIMB_INDEX = 41;
            $this->NUM_CONTROLS_INDEX = 42;
            $this->START_PUNCH_INDEX = 44;
            $this->FIRST_SPLIT_INDEX = 46;
            $this->FIRSTNAME_INDEX = 4;
        } else {
            $this->NAME_INDEX = 3;
            $this->START_TIME_INDEX = 7;
            $this->TOTAL_TIME_INDEX = 9;
            $this->CLUB_INDEX = 13;
            $this->CLASS_INDEX = 16;
            $this->COURSE_INDEX = 37;
            $this->DISTANCE_INDEX = 38;
            $this->CLIMB_INDEX = 39;
            $this->NUM_CONTROLS_INDEX = 40;
            $this->START_PUNCH_INDEX = 42;
            $this->FIRST_SPLIT_INDEX = 44;
            $this->FIRSTNAME_INDEX = -1; // Set to -1 so it will raise an error is it
            // is used.
        }
    }

    /**
     * Determines the character used to delimit fields in the file by inspecting
     * the header line.
     * 
     * @param SI CSV header line
     * @return delimiter character
     */
    function setDelimiter($line) {
        $REQUIIRED_NO = 3;
        $delimiter = "";

        if (substr_count($line, ';') >= $REQUIIRED_NO) {
            $delimiter = ';';
        } else
            if (substr_count($line, ',') >= $REQUIIRED_NO) {
                $delimiter = ',';
            } else
                if (substr_count($line, '\\') >= $REQUIIRED_NO) {
                    $delimiter = '\\';
                } else
                    if (substr_count($line, '\t') >= $REQUIIRED_NO) {
                        $delimiter = '\t';
                    }
        return ($delimiter);
    }

    /**
     * Sets the file Format.
     * 
     * The default format for SportIdent CSV output changed in 2003. The name
     * was separated into surname and firstname. The first name colum headings
     * defined in the the SportIdent translation file, OEinzel.mlf, are unique.
     * 
     *  @param line headerLine header line in the SportIdent csv file
     */
    function setFileFormat($headerLine) {
            $isOE2002OrLater = (strpos($headerLine, "First name") != FALSE) || // English
         (strpos($headerLine, "Förnamn") != FALSE) || // Swedish
         (strpos($headerLine, "Prénom") != FALSE) || // French
         (strpos($headerLine, "Nome") != FALSE) || // Italian
         (strpos($headerLine, "Jméno (køest.)") != FALSE) || // Chetz
         (strpos($headerLine, "Utónév") != FALSE) || // Magyar
         (strpos($headerLine, "Fornavn") != FALSE) || // Danish
         (strpos($headerLine, "Imiê") != FALSE) || // Polish
         (strpos($headerLine, "Ime") != FALSE) || // Hungerian
         (strpos($headerLine, "Nombre") != FALSE) || // Spanish
     (strpos($headerLine, "Vorname") != FALSE); // Austrian

        return ($isOE2002OrLater);
    }
}
?>