
<!DOCTYPE html>
<html lang="eng" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Data</title>

    <link rel="stylesheet" href="css/style.css">

    <?php require 'header.php' 
	?>

  </head>

  <div class="bannerExport">
    <h1> EXPORT </h1>
  </div>

  <body>

    <div class="Description">
      <p class="DataDescription">
        This is data from the meteorological station of UNESP, locate in Sorocaba Brasil 23.47906125 South and 47.41717289 West.
        <br>
        You will be able to collect the data from this page by completing the form and clic on the button «export» to export your data into a csv file.
        <br>
        There are a few numbers of filters like days, date, time, weather don’t hesitate to use it to retrieve what you want.
        <br> Don't hesitate to contact us if you want more information about the export part.
        <br>
      </p>
    </div>


    <div class="ContainerDataForm" id="dataForm">

      <h1 class="titleData"> CSV DATA FORM </h1>
	  
	  <form method="get" class="graphForm2" action=graphDisplay2.php>
	  <button type="Graph" class="graphSubmit">Graph</button>
	  <input type="date" placeholder="yyyy-mm-dd" id="datetimeGraph" name="GraphDate" required>
	  </form>

      <!-- the export data form -->
      <form method="post" class="dataForm" action="ExportScript.php" enctype="multipart/form-data">

         <p class="FormDesc"> Give a name to the file with the data to be downloaded </p>
         <input type="text" class="inputNameFile" name="nameFile" placeholder="name of the file" required>

         <p class="FormDesc"> Select the Data to add </p>


      <!-- data selection cheboxes -->
         <div class="containerDataInfo" >

           <input id="toggle1" type="checkbox" name="case1" checked>
           <label for="toggle1"> TIMESTAMP </label>

           
           <input id="toggle2" type="checkbox" name="case2">
           <label for="toggle2"> Energy1 </label>
            

           <input id="toggle3" type="checkbox" name="case3">
           <label for="toggle3"> Energy2 </label>

           <input id="toggle4" type="checkbox" name="case4">
           <label for="toggle4"> Temperature </label>

           <input id="toggle5" type="checkbox" name="case5">
           <label for="toggle5"> Humidity </label>

           <input id="toggle6" type="checkbox" name="case6">
           <label for="toggle6"> Rain </label>

           <input id="toggle7" type="checkbox" name="case7">
           <label for="toggle7"> Radiation </label>

           

         </div>

      <!-- date filter -->
         <p class="FormDesc"> Select Date </p>
         <p class="FormDesc"> From <input type="date" placeholder="yyyy-mm-dd" id="datetime1" name="Plage1" required>
             to <input type="date" placeholder="yyyy-mm-dd" id="datetime2" name="Plage2" required> </p>

      <!-- button "others" to show other filters -->
        <input id="how-other" name="how" type="checkbox">
        <label for="how-other" class="side-label">Other...</label>

        <div class = "more-Filters">

      <!-- hours filter -->
         <p class="FormDesc"> Select Time </p>
         <p> From <input type="time" id="time1" name="hour1" min="00:00" max="23:59">
             to <input type="time" id="time2" name="hour2" min="00:00" max="23:59"> </p>

      <!-- days filter -->
         <p class="FormDesc"> Select Days Filter </p>

         <p class=days>
           <input type="checkbox" class="day" id="Everyday" name="Everyday" label="everyday">
           <label for="Everyday">Everyday</label>
         </p>
         <p>
           <input type="checkbox" class="day" id="Monday" name="Monday">
           <label for="Monday">Monday</label>
         </p>
         <p class=days>
           <input type="checkbox" class="day" id="Tuesday" name="Tuesday">
           <label for="Tuesday">Tuesday</label>
         </p>
         <p class=days>
           <input type="checkbox" class="day" id="Wednesday" name="Wednesday">
           <label for="Wednesday">Wednesday</label>
         </p>
         <p class=days>
           <input type="checkbox" class="day" id="Thursday" name="Thursday">
           <label for="Thursday">Thursday</label>
         </p>
         <p class=days>
           <input type="checkbox" class="day" id="Friday" name="Friday">
           <label for="Friday">Friday</label>
         </p>
         <p class=days>
           <input type="checkbox" class="day" id="Saturday" name="Saturday">
           <label for="Saturday">Saturday</label>
         </p>
         <p class=days>
           <input type="checkbox" class="day" id="Sunday" name="Sunday">
           <label for="Sunday">Sunday</label>
         </p>

      <!-- weather filter -->
         <p class="FormDesc"> Select Weather Filter </p>
         <input type="checkbox" class="weather" id="Sunny" name="Sunny">
         <label for="Sunny">Sunny</label>
         <input type="checkbox" class="weather" id="Rainy" name="Rainy">
         <label for="Rainy">Rainy</label>
         <input type="checkbox" class="weather" id="Cloudy" name="Cloudy">
         <label for="Cloudy">Cloudy</label>


      <!-- format filter -->
         <p class="FormDesc"> Select the format for the numbers</p>
         <input type="radio" class="dotcoma" name="dotcoma" value="dot" checked>
         <label for="dotcoma">with dot</label>
         <input type="radio" class="dotcoma" name="dotcoma" value="coma">
         <label for="dotcoma">with coma</label>

         <p></p>
         <p></p>

        </div>

      <!-- validation button -->

        <div class="validateContainer">
          <button type="button" class="btn-validate" onclick="openForm()" name="exportForm">export</button>
        </div>


      <!-- POPUP COUNTRY-->

        <div class="block" id="block">  </div>
          <div class="form-popup" id="confirmCountry">
            <h1>Export</h1>

            <p>To help us to improve the website, please select your country<p>

            <select class="countryForm" name="country" >
              <option value="Afghanistan">Afghanistan</option>
              <option value="Åland Islands">Åland Islands</option>
              <option value="Albania">Albania</option>
              <option value="Algeria">Algeria</option>
              <option value="American Samoa">American Samoa</option>
              <option value="Andorra">Andorra</option>
              <option value="Angola">Angola</option>
              <option value="Anguilla">Anguilla</option>
              <option value="Antarctica">Antarctica</option>
              <option value="Antigua and Barbuda">Antigua and Barbuda</option>
              <option value="Argentina">Argentina</option>
              <option value="Armenia">Armenia</option>
              <option value="Aruba">Aruba</option>
              <option value="Australia">Australia</option>
              <option value="Austria">Austria</option>
              <option value="Azerbaijan">Azerbaijan</option>
              <option value="Bahamas">Bahamas</option>
              <option value="Bahrain">Bahrain</option>
              <option value="Bangladesh">Bangladesh</option>
              <option value="Barbados">Barbados</option>
              <option value="Belarus">Belarus</option>
              <option value="Belgium">Belgium</option>
              <option value="Belize">Belize</option>
              <option value="Benin">Benin</option>
              <option value="Bermuda">Bermuda</option>
              <option value="Bhutan">Bhutan</option>
              <option value="Bolivia">Bolivia</option>
              <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
              <option value="Botswana">Botswana</option>
              <option value="Bouvet Island">Bouvet Island</option>
              <option value="Brazil" selected>Brazil</option>
              <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
              <option value="Brunei Darussalam">Brunei Darussalam</option>
              <option value="Bulgaria">Bulgaria</option>
              <option value="Burkina Faso">Burkina Faso</option>
              <option value="Burundi">Burundi</option>
              <option value="Cambodia">Cambodia</option>
              <option value="Cameroon">Cameroon</option>
              <option value="Canada">Canada</option>
              <option value="Cape Verde">Cape Verde</option>
              <option value="Cayman Islands">Cayman Islands</option>
              <option value="Central African Republic">Central African Republic</option>
              <option value="Chad">Chad</option>
              <option value="Chile">Chile</option>
              <option value="China">China</option>
              <option value="Christmas Island">Christmas Island</option>
              <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
              <option value="Colombia">Colombia</option>
              <option value="Comoros">Comoros</option>
              <option value="Congo">Congo</option>
              <option value="Congo, The Democratic Republic of The">Congo, The Democratic Republic of The</option>
              <option value="Cook Islands">Cook Islands</option>
              <option value="Costa Rica">Costa Rica</option>
              <option value="Cote D'ivoire">Cote D'ivoire</option>
              <option value="Croatia">Croatia</option>
              <option value="Cuba">Cuba</option>
              <option value="Cyprus">Cyprus</option>
              <option value="Czech Republic">Czech Republic</option>
              <option value="Denmark">Denmark</option>
              <option value="Djibouti">Djibouti</option>
              <option value="Dominica">Dominica</option>
              <option value="Dominican Republic">Dominican Republic</option>
              <option value="Ecuador">Ecuador</option>
              <option value="Egypt">Egypt</option>
              <option value="El Salvador">El Salvador</option>
              <option value="Equatorial Guinea">Equatorial Guinea</option>
              <option value="Eritrea">Eritrea</option>
              <option value="Estonia">Estonia</option>
              <option value="Ethiopia">Ethiopia</option>
              <option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
              <option value="Faroe Islands">Faroe Islands</option>
              <option value="Fiji">Fiji</option>
              <option value="Finland">Finland</option>
              <option value="France">France</option>
              <option value="French Guiana">French Guiana</option>
              <option value="French Polynesia">French Polynesia</option>
              <option value="French Southern Territories">French Southern Territories</option>
              <option value="Gabon">Gabon</option>
              <option value="Gambia">Gambia</option>
              <option value="Georgia">Georgia</option>
              <option value="Germany">Germany</option>
              <option value="Ghana">Ghana</option>
              <option value="Gibraltar">Gibraltar</option>
              <option value="Greece">Greece</option>
              <option value="Greenland">Greenland</option>
              <option value="Grenada">Grenada</option>
              <option value="Guadeloupe">Guadeloupe</option>
              <option value="Guam">Guam</option>
              <option value="Guatemala">Guatemala</option>
              <option value="Guernsey">Guernsey</option>
              <option value="Guinea">Guinea</option>
              <option value="Guinea-bissau">Guinea-bissau</option>
              <option value="Guyana">Guyana</option>
              <option value="Haiti">Haiti</option>
              <option value="Heard Island and Mcdonald Islands">Heard Island and Mcdonald Islands</option>
              <option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option>
              <option value="Honduras">Honduras</option>
              <option value="Hong Kong">Hong Kong</option>
              <option value="Hungary">Hungary</option>
              <option value="Iceland">Iceland</option>
              <option value="India">India</option>
              <option value="Indonesia">Indonesia</option>
              <option value="Iran, Islamic Republic of">Iran, Islamic Republic of</option>
              <option value="Iraq">Iraq</option>
              <option value="Ireland">Ireland</option>
              <option value="Isle of Man">Isle of Man</option>
              <option value="Israel">Israel</option>
              <option value="Italy">Italy</option>
              <option value="Jamaica">Jamaica</option>
              <option value="Japan">Japan</option>
              <option value="Jersey">Jersey</option>
              <option value="Jordan">Jordan</option>
              <option value="Kazakhstan">Kazakhstan</option>
              <option value="Kenya">Kenya</option>
              <option value="Kiribati">Kiribati</option>
              <option value="Korea, Democratic People's Republic of">Korea, Democratic People's Republic of</option>
              <option value="Korea, Republic of">Korea, Republic of</option>
              <option value="Kuwait">Kuwait</option>
              <option value="Kyrgyzstan">Kyrgyzstan</option>
              <option value="Lao People's Democratic Republic">Lao People's Democratic Republic</option>
              <option value="Latvia">Latvia</option>
              <option value="Lebanon">Lebanon</option>
              <option value="Lesotho">Lesotho</option>
              <option value="Liberia">Liberia</option>
              <option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
              <option value="Liechtenstein">Liechtenstein</option>
              <option value="Lithuania">Lithuania</option>
              <option value="Luxembourg">Luxembourg</option>
              <option value="Macao">Macao</option>
              <option value="Macedonia, The Former Yugoslav Republic of">Macedonia, The Former Yugoslav Republic of</option>
              <option value="Madagascar">Madagascar</option>
              <option value="Malawi">Malawi</option>
              <option value="Malaysia">Malaysia</option>
              <option value="Maldives">Maldives</option>
              <option value="Mali">Mali</option>
              <option value="Malta">Malta</option>
              <option value="Marshall Islands">Marshall Islands</option>
              <option value="Martinique">Martinique</option>
              <option value="Mauritania">Mauritania</option>
              <option value="Mauritius">Mauritius</option>
              <option value="Mayotte">Mayotte</option>
              <option value="Mexico">Mexico</option>
              <option value="Micronesia, Federated States of">Micronesia, Federated States of</option>
              <option value="Moldova, Republic of">Moldova, Republic of</option>
              <option value="Monaco">Monaco</option>
              <option value="Mongolia">Mongolia</option>
              <option value="Montenegro">Montenegro</option>
              <option value="Montserrat">Montserrat</option>
              <option value="Morocco">Morocco</option>
              <option value="Mozambique">Mozambique</option>
              <option value="Myanmar">Myanmar</option>
              <option value="Namibia">Namibia</option>
              <option value="Nauru">Nauru</option>
              <option value="Nepal">Nepal</option>
              <option value="Netherlands">Netherlands</option>
              <option value="Netherlands Antilles">Netherlands Antilles</option>
              <option value="New Caledonia">New Caledonia</option>
              <option value="New Zealand">New Zealand</option>
              <option value="Nicaragua">Nicaragua</option>
              <option value="Niger">Niger</option>
              <option value="Nigeria">Nigeria</option>
              <option value="Niue">Niue</option>
              <option value="Norfolk Island">Norfolk Island</option>
              <option value="Northern Mariana Islands">Northern Mariana Islands</option>
              <option value="Norway">Norway</option>
              <option value="Oman">Oman</option>
              <option value="Pakistan">Pakistan</option>
              <option value="Palau">Palau</option>
              <option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied</option>
              <option value="Panama">Panama</option>
              <option value="Papua New Guinea">Papua New Guinea</option>
              <option value="Paraguay">Paraguay</option>
              <option value="Peru">Peru</option>
              <option value="Philippines">Philippines</option>
              <option value="Pitcairn">Pitcairn</option>
              <option value="Poland">Poland</option>
              <option value="Portugal">Portugal</option>
              <option value="Puerto Rico">Puerto Rico</option>
              <option value="Qatar">Qatar</option>
              <option value="Reunion">Reunion</option>
              <option value="Romania">Romania</option>
              <option value="Russian Federation">Russian Federation</option>
              <option value="Rwanda">Rwanda</option>
              <option value="Saint Helena">Saint Helena</option>
              <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
              <option value="Saint Lucia">Saint Lucia</option>
              <option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
              <option value="Saint Vincent and The Grenadines">Saint Vincent and The Grenadines</option>
              <option value="Samoa">Samoa</option>
              <option value="San Marino">San Marino</option>
              <option value="Sao Tome and Principe">Sao Tome and Principe</option>
              <option value="Saudi Arabia">Saudi Arabia</option>
              <option value="Senegal">Senegal</option>
              <option value="Serbia">Serbia</option>
              <option value="Seychelles">Seychelles</option>
              <option value="Sierra Leone">Sierra Leone</option>
              <option value="Singapore">Singapore</option>
              <option value="Slovakia">Slovakia</option>
              <option value="Slovenia">Slovenia</option>
              <option value="Solomon Islands">Solomon Islands</option>
              <option value="Somalia">Somalia</option>
              <option value="South Africa">South Africa</option>
              <option value="South Georgia and The South Sandwich Islands">South Georgia and The South Sandwich Islands</option>
              <option value="Spain">Spain</option>
              <option value="Sri Lanka">Sri Lanka</option>
              <option value="Sudan">Sudan</option>
              <option value="Suriname">Suriname</option>
              <option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
              <option value="Swaziland">Swaziland</option>
              <option value="Sweden">Sweden</option>
              <option value="Switzerland">Switzerland</option>
              <option value="Syrian Arab Republic">Syrian Arab Republic</option>
              <option value="Taiwan, Province of China">Taiwan, Province of China</option>
              <option value="Tajikistan">Tajikistan</option>
              <option value="Tanzania, United Republic of">Tanzania, United Republic of</option>
              <option value="Thailand">Thailand</option>
              <option value="Timor-leste">Timor-leste</option>
              <option value="Togo">Togo</option>
              <option value="Tokelau">Tokelau</option>
              <option value="Tonga">Tonga</option>
              <option value="Trinidad and Tobago">Trinidad and Tobago</option>
              <option value="Tunisia">Tunisia</option>
              <option value="Turkey">Turkey</option>
              <option value="Turkmenistan">Turkmenistan</option>
              <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
              <option value="Tuvalu">Tuvalu</option>
              <option value="Uganda">Uganda</option>
              <option value="Ukraine">Ukraine</option>
              <option value="United Arab Emirates">United Arab Emirates</option>
              <option value="United Kingdom">United Kingdom</option>
              <option value="United States">United States</option>
              <option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
              <option value="Uruguay">Uruguay</option>
              <option value="Uzbekistan">Uzbekistan</option>
              <option value="Vanuatu">Vanuatu</option>
              <option value="Venezuela">Venezuela</option>
              <option value="Viet Nam">Viet Nam</option>
              <option value="Virgin Islands, British">Virgin Islands, British</option>
              <option value="Virgin Islands, U.S.">Virgin Islands, U.S.</option>
              <option value="Wallis and Futuna">Wallis and Futuna</option>
              <option value="Western Sahara">Western Sahara</option>
              <option value="Yemen">Yemen</option>
              <option value="Zambia">Zambia</option>
              <option value="Zimbabwe">Zimbabwe</option>
            </select>


            <button type="submit" class="btn-export" name="export">Export</button>
            <button type="button" class="btn cancel" onclick="closeForm()">Cancel</button>
			

          </div>


      <!-- -->


      </form>

    </div>

  </body>

  <?php require 'footer.php'; ?>

</html>

<script>

  function openForm() {

    document.getElementById("block").style.display = "block";
    document.getElementById("confirmCountry").style.display = "block";
    //document.body.style.overflow = "hidden";

  }

  function closeForm() {
    document.getElementById("block").style.display = "none";
    document.getElementById("confirmCountry").style.display = "none";
    //document.body.style.overflow = "visible";

  }


</script>
