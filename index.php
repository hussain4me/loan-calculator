<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <title>Loan Calculator</title>
</head>
<body>

    <div class="content ">

    <div class="row justify-content-center align-items-center vh-80">
    <div class="col-md-6">
        <h1 class ="text text-center">Loan Calculator</h1>
        <form>

            <label for="principal"><b>Principal:</b></label>
            <input type="number" id="principal" name="principal" class="form-control" required>
            <span class="text text-danger .principalErr" id ="principalErr"></span>
            <br><br>
        
            <label for="rate"><b>Rate (%):</b></label>
            <input type="number" id="rate" name="rate" step="0.01" class="form-control" required>
            <span class="text text-danger .rateErr" id ="rateErr"></span><br><br>
        
            <label for="tenor"><b>Tenure:</b></label>
            <input type="number" id="tenor" name="tenor" class="form-control" required>
            <span class="text text-danger .tenorErr" id="tenorErr"></span><br><br>
        
            <input type="button" value="Calculate" id="Calculate" name="Calculate" class="btn btn-primary pull-left">

            <input type="button" value="Reset" id="reset" name="reset"  onclick="resetbtn()" class="btn btn-danger pull-right">
        </form>
    </div>
    </div>


    <div class="row justify-content-center align-items-center d-none" id="result" >
    <div class="col-md-6">
        <h2 class ="text text-center"><b>Loan Detail</b></h2>
          <div class="card">
            <label for="principal"><b>Principal: <span for="" id="principalResult"></span></b></label>
            
            <br><br>
            <label for="rate"><b>Rate : <span for="" id="rateResult"></span>%</b></label>
            
            
            <br><br>
            <label for="tenor"><b>Tenor: <span for="" id="tenorResult"></span></b></label>
            
            <br><br>
            <h5 class ="text text-center"><b>Monthly Repayment : ₦</b> <span id="monthlypay"></span></h5>
          </div>
        
          <h5 class ="text text-left mt-2"><b>Scheduled Dates for Repayment</b></h5>
          <ul style="list-style-type: None" id="scheduledMonths">
          </ul>

    </div>
    </div>
    </div>


    <script>

          function validateInputs(principal,rate,tenor){
            let principalErrorElem = document.getElementById("principalErr");
            let rateErrorElem = document.getElementById("rateErr");
            let tenorErrorElem = document.getElementById("tenorErr");
            var returnVal = true
            if(isNaN(principal) || principal <= 0) {
                principalErrorElem.innerText = 'Please enter a valid principal value';
                returnVal = false;
            }else principalErrorElem.innerText = '';
            if(isNaN(rate) || tenor <= 0){
                rateErrorElem.innerText = "Please enter a valid rate";
                returnVal = false;
            }else rateErrorElem.innerText = "";
            if(isNaN(tenor) || tenor <= 0){
                tenorErrorElem.innerText = "Please enter a valid tenor.";
                returnVal = false;;
            }else tenorErrorElem.innerText = "";

            return returnVal;
        }


        // Reset the form
        function resetbtn(){
            if (window.confirm("Are you sure want to reset the calculator?")) document.getElementById('result').classList.add('d-none'); else return false
        }

        function clearScheduleList(list){
            while (list.firstChild) {
                list.removeChild(list.firstChild);
                }
        }

        document.getElementById("Calculate").addEventListener("click", () =>{
            
            fetchData();
           
        })



        function fetchData() {

            let principal = document.getElementById("principal").value;
            let rate = document.getElementById("rate").value;
            let tenor = document.getElementById("tenor").value;            

            // Validate the inputs
            if (validateInputs(principal,rate,tenor) == false) return false

            url =`loan-compute.php?principal=${principal}&rate=${rate}&tenor=${tenor}`;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    // Handle the data received from the API
                    console.log(data.monthlyPayment); 
                    console.log('length of ', Object.keys(data).length); 
                    // document.getElementById("monthlypay").innerText= data.monthlyPayment;
                    if(Object.keys(data).length> 0){
                        document.getElementById('result').classList.remove('d-none');
                        document.getElementById("principalResult").innerText= parseFloat(principal).toLocaleString();
                        document.getElementById("rateResult").innerText= rate.toLocaleString();
                        document.getElementById("tenorResult").innerText= tenor.toLocaleString();
                        document.getElementById("monthlypay").innerText= data.monthlyPayment.toLocaleString();
                        var scheduleMonth = data.scheduleRepayMonths
                        
                        let scheduledList = document.getElementById("scheduledMonths");
                        clearScheduleList(scheduledList)
                        scheduleMonth.forEach(month =>{

                            let li = document.createElement("li");
                            li.textContent = month;
                            scheduledList.appendChild(li);

                        })// For example, log the data to the console

                    }

                    
                })
                .catch(error => {
                    // Handle errors if the request fails
                    console.error('Error:', error);
                });
        }

        
    </script>
     
</body>
</html>