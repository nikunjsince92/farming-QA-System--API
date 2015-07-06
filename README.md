# farming-QA-System--API-
This API is developed to answer the farming related queries. Developed in PHP. Output in JSON.

The format of our API is http://freakengineers.com/mtp/niks/index.php (base URL). 
The response is encoded in JSON.
Here are the possible parameters placed in the request URL.
$query=N //Mandatory, N (string) represents the query from the user.
$lcount=M //Optional, M represents the no. of lines in summary.

Example-1
If the API request is made to http://freakengineers.com/mtp/niks/index.php?query=iwanttoplantwatermelon i.e. if the lcount parameter is not present. So for such cases we have designed a smart summarization method.
Smart Summarisation - In this first the length of the original text will be calculated,  then according to that, based on some selfmade rules, the length of the summary will be decided by the system.

Example-2
If the API request is made to http://freakengineers.com/mtp/niks/index.php?query=havetoplantladyfinger&lcount=3,5,7
Value of lcount - lcount can take maximum upto three values, which means like here, if we pass lcount=3,5,7 then the summarization will be shown in 3 lines, 5 lines and 7 lines. Also the smart summarization (default) will be shown. The values of lcount should be separated by comma (,) and there should not be any space.

Example-3
If the API request is made to http://freakengineers.com/mtp/niks/index.php?query=growapple&lcount=2,3,7,5,1 i.e. If more then 3 values are passed in lcount, then it will show the summary for initial 3 values along with the smart summarization
(default) and will display an error for the rest ones.
    {"exception":"Max line count exceeded"}
    
Type of Queries ($query parameter)- This system is basically designed to answer the agriculture related queries. It can answer the plantation queries for almost all the plants and can also answer many other queries related to pests, pesticides, farm etc.
Some examples are-
 ways to Make a Natural Insectcide
 How can we Make a Crop Circle
 Want to plant ladynger.
 how to graft plants.
 plant pine trees.
 how to protect plants from pests.
 how can we Handle and Apply Pesticides Safely
 buSinEss plaNs for farming nd RaIsing Livestock
 how to Practise Sustainable agriculture
 ways to Stay Safe on a Farm
 how can we Start a Farm
 grow apple.
 how to get a government grant for a farm
 planting Roses
 how can Make a Living on a Small Farm
NOTE: Spelling mistakes and grammatical errors will be handled automatically.
