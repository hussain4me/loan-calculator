<?php



       define('PERCENTAGE', 100);
       define('MONTHS_IN_YEAR', 12);

       $principal = (float)$_GET['principal'];
       $annualRate = (int)$_GET['rate'];

       $monthlyRate = ($annualRate / 100) / MONTHS_IN_YEAR;


       $durationMonths = (float)$_GET['tenor'];

        // Calculate loan  the numerator
        $numerator = $principal * $monthlyRate *  pow(1 + $monthlyRate, $durationMonths);
        $denominator = pow(1 + $monthlyRate, $durationMonths) - 1; // Calculate loan  the denumerator
        
        $monthlyPayment = $numerator / $denominator; // Calculate loan  the monthly repayment

        $scheduleRepayMonths = array();
    
//  Generate schedule dates
        $currentDate = strtotime(date('Y-m-d'));
        $ParticularDay = date('d');  

        for ($i=1; $i <= $durationMonths ; $i++) { 

            $nextParticularDate = strtotime("+$i months", $currentDate);
            
            // Format the date based on the particular day of the month
            $formattedDate = date('Y-m', $nextParticularDate) . '-' . $ParticularDay;

            // $scheduleRepayMonths[date('F', strtotime($formattedDate))] =  date('F j, Y', strtotime($formattedDate));
            $scheduleRepayMonths[] =  date('F j, Y', strtotime($formattedDate));
        }


        echo  json_encode(['scheduleRepayMonths'=>$scheduleRepayMonths, 'monthlyPayment'=> $monthlyPayment]);



        // number_format($monthlyPayment, 2)



       

?>
