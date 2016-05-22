
Team Name:				Agents of SECURITY
Team Members:				Varun Chandra Jammula
					Azhaku Sakthi Vel Muthu Krishnan
						
Description of the Project:
---------------------------

How to Run(Using XAMPP Service):

1.	Start Apache service using XAMPP.

2.	Copy the the folder 'AoS_XSS' into the 'C:\xampp\htdocs\' folder

3.  	Open browser and enter url 'localhost/AoS_XSS/'

4.	You'll notice a text box with label 'Enter a Message'

5. 	Use include “XSSGuard.php” to start using the library in your code.

6. 	Next create an object using $xssguard = new XSSGuard();

7.	Using this object, you can call any function that you need.

8.	The list of functions provided in the library are as follows:
	
function XSSecho($var) 					 ——>  Similar to PHP's echo call, but uses htmlentities to avoid 							      	XSS
function XSSprint($var)					 ——>  Similar to PHP's print call, but uses htmlentities to avoid 							      	XSS
function XSSprint_r($var)				 ——>  Similar to PHP's print_r call, but uses htmlentities to avoid 							XSS
function sanitize($input, $flag, $min, $max)		 ——>  This function takes an unsanitized string and returns the 								sanitized string, based on flags set.
function sanitize_float($float, $min='', $max='')        ——>  input float, returns ONLY the float (no extraneous characters								)
function sanitize_int($integer, $min='', $max='')        ——>  input integer, returns ONLY the integer (no extraneous 									characters)
function sanitize_paranoid_string($string, $min='', $max='') ——>  Returns string stripped of all non alphanumeric 										characters
function sanitize_shell_string($string, $min='', $max='')    ——>  Sanitizes the strings that are provided to system() 										call and avoid shell injection.
function sanitize_sql_string($link, $string)		      ——>  This function sanitizes strings that will inserted in 									to the query or the entire string as query
function sanitize_ldap_string($string, $min='', $max='')      ——>  Sanitizes LDAP strings that maybe injected with a 									string
function sanitize_html_string($string)			 ——>  Sanitizes string that needs to be embedded into HTML page
	
	
To check the normal case
------------------------

5.	Enter 'XSS' in the text box and press 'Submit Query'

6.	The output on the screen turns out as below:
		XSSGuard Enabled		
		Your message : XSS            
		Your message : XSS
		Your message : XSS

7.	This shows that the entered string is echoed perfectly in all three cases (Case 1, Case 2, Case 3)
	where 
	Case 1:	$xssguard->XSSecho(String)			(Uses an object of our class "XSSGuard")
	Case 2:	echo String					(The default echo function of PHP)
	Case 3:	echo $xssguard->sanitize(String)		(Uses an object of our class "XSSGuard")

To check the vulnerability case
-------------------------------

8.	Enter "<script>alert('XSS')</script>" in the text box and press "Submit Query"

9.	A pop up shows up with a message 'XSS'. After 'OK' is pressed the output on the screen turns up as: 
		XSSGuard Enabled		
		Your message : XSS            
		Your message : 
		Your message : XSS

10.	The display of string 'XSS' in two of the three cases(i.e. Case1, Case 3) is the output which  
	suggests that the malicious String "<script>alert('XSS')</script>" doesn't result in a pop up and is rather echoed perfectly.
	Whereas the display of the pop up displaying the content 'XSS' means that the vulnerability is induced.
	
11. 	Thus it is clear that the usage of the Object of "XSSGuard" and it's functions nullifies the vulnerability effect.

						
