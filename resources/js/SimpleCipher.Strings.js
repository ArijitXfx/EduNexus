/**
 * Created by Sudipta Saha on 7/17/2017.
 */

/**
 * This function validates a string if it is not a first or last word in a sentence.
 * @param word String that needs to be validated.
 *
 * @return True if the String is a valid word, else false.
 */
function String_isMiddleWord(word){

    var re = new RegExp("^([A-Z]{0,1}[a-z]+(-[a-z]){0,1}[a-z]*[']{0,1}[a-z]*)$");
    return re.test(word);
}

/**
 * This function validates a string if it is a starting word in a sentence.
 * @param word String that needs to be validated.
 *
 * @return True if the String is a valid word, else false.
 */
function String_isFirstWord(word){
    var re = new RegExp("^([A-Z]{1}[a-z]+(-[a-z]){0,1}[a-z]*[']{0,1}[a-z]*)$");
    return re.test(word);

}

/**
 * This function validates a string if it is a terminating word in a sentence.
 * @param word String that needs to be validated.
 *
 * @return True if the String is a valid word, else false.
 */
function String_isDefaultLastWord(word){
    var re = new RegExp("^([A-Z]{0,1}[a-z]+(-[a-z]){0,1}[a-z]*[']{0,1}[a-z]*)+[']{0,1}([?!.]{1})$");
    return re.test(word);
}

/**
 * This function validates a string if it is a terminating word in a sentence. The terminating parameters are user defined.
 * @param word String that needs to be validated.
 *
 * @param delimeters The ending parameters for the word to validate word termination. The parameters
 * should be placed in sequence without any gaps. For backslash, you need to put '\\\\' because the
 * string parser will remove two of them when "de-escaping" it for the string, and then the regex needs
 * two for an escaped regex backslash.
 * <pre>For example : isCustomLastWord(yourString,"/}\\\\") will check for /, }, \ as word termination parameters.</pre>
 *
 * @return True if the String is a valid word, else false.
 */
function String_isCustomLastWord(word,delimeters){
    var re = new RegExp("^([A-Z]{0,1}[a-z]+(-[a-z]){0,1}[a-z]*[']{0,1}[a-z]*)+[']{0,1}(["+delimeters+"]{1})$");
    return re.test(word);
}


/**
 * This function validates a string having one or more words with custom seperator or seperators. The seperators are user defined.
 * @param word String that needs to be validated.
 *
 * @param seperator The ending parameter for each word to validate word termination. The parameters
 * should be placed in sequence without any gaps. For backslash, you need to put '\\\\' because the
 * string parser will remove two of them when "de-escaping" it for the string, and then the regex needs
 * two for an escaped regex backslash.
 * <pre>For example : isCustomLastWord(yourString,"/}\\\\") will check for /, }, \ as word termination parameters.</pre>
 *
 * @return True if the String is a valid word, else false.
 */
function String_isWordWithSeparator(word,seperator){
  var re = new RegExp("^(([A-Z]{0,1}[a-z]+(-[a-z]){0,1}[a-z]*[']{0,1}[a-z]*)+[']{0,1})((["+delimeters+"]{1})\\s(([A-Z]{0,1}[a-z]+(-[a-z]){0,1}[a-z]*[']{0,1}[a-z]*)+[']{0,1}))*$");
  return re.test(word);
}

/**
 * This function validates if a string is valid a sentence.
 * @param sentence String that needs to be validated.
 *
 * @return True if the String is a valid word, else false.
 */
function String_isSentence(sentence){
    // Pattern firstWord=Pattern.compile("^([\"]{0,1}[A-Z]{0,1}(([a-z]+[-]{0,1}[']{0,1}[a-z]*)+[']{0,1})(\\?\"){0,1}[\"]{0,1}[,;]{0,1})*$");
    var lastWord=new RegExp("^([\"]{0,1}[A-Z]{0,1}(([a-z]+(-[a-z]){0,1}[a-z]*[']{0,1}[a-z]*))((\\?\")|(!\")){0,1}[\"]{0,1}[,;]{0,1})[.!?]{1}$");

    var midWord=new RegExp("^([\"]{0,1}[A-Z]{0,1}(([a-z]+[-]{0,1}[a-z]*[']{0,1}[a-z]*))((\\?\")|(!\")){0,1}[\"]{0,1}[,;]{0,1})$");
    quoteCount=0;

    cool=sentence.split(/\s/g);

    for(i=0;i<cool.length;i++){
        if(cool[i].indexOf("\"")!= -1)
            quoteCount++;
       // alert(cool[i]);
        if(i==cool.length-1 && lastWord.test(cool[i])==false)
            return false;
        else if(i!=cool.length-1 && midWord.test(cool[i])==false)
            return false;

        // System.out.print("\n"+i+" = "+cool[i]+" matched : "+matcher.matches());


    }
    if(quoteCount%2!=0)
        return false;
    return true;
}

/**
 * This function validates if a string is valid a phone number for an user defined format.
 * @param phoneno String that needs to be validated.
 *
 * @param format The custom format of the phone number.
 * <pre>
 * For example XXX-XXX where X has to be in uppercase and X represents a possible digit.
 * </pre>
 * @return True if the String is a valid word, else false.
 *
 * @throws InvalidFormatException Throws exception on format mismatch.
 */
function String_isPhoneno(phoneno,format){
    var re = new RegExp("^X+([-]{1}X+)*$");

    if(re.test(format)){
        sb="^";
        st=format.split("-");
        for(i=0;i<st.length;i++){
            sb=sb.concat("([0-9]{"+st[i].length+"})-");
        }
        sb=sb.slice(0,-1);
        sb=sb.concat("$");
        pattern= new RegExp(sb);
        return pattern.test(phoneno);

    }
    else{
        throw("InvalidFormatException : Not a valid phone number format.");
    }

}

/**
 * This function validates if a string is valid a valid name. The honorific is allowed if user defined.
 * @param name String that needs to be validated.
 *
 * @param checkHonorific True if name includes a honorific else false.
 *
 * @param isAllUpperCase True is all the letters are in uppercase, else false. Then it checks if the first letter of every starting word is in uppercase.
 *
 * @return True if the String is a valid word, else false.
 */
function String_isName(name,checkHonorific,isAllUpperCase){
    if(isAllUpperCase)
        range="[A-Z]";
    else
        range="[a-z]";

    if(!checkHonorific)
        pattern=new RegExp("^(([A-Z]{1}"+range+"+))(\\s([A-Z]{1}"+range+"+))+$");
    else
        pattern=new RegExp("^([A-z]{1}"+range+"{0,4}[.]{1}) (([A-Z]{1}"+range+"+))(\\s([A-Z]{1}"+range+"+))+$");

    return pattern.test(name);
}


/**
 * This function checks if an input string has binary bits, i.e if they are made up of 1s and 0s.
 * @param binary String that needs to be validated.
 * @return True if the String is a valid binary input, else false.
 */
function String_isBinary(binary){
    pattern=new RegExp("^[01]+$");
    return pattern.test(binary);
}

/**
 * This function checks if an input string has hexadecimal bits, i.e if they are made up of 0-9 and A-F.
 * @param hex String that needs to be validated.
 * @return True if the String is a valid hexadecimal input, else false.
 */
function String_isBinaryisHex(hex){
    pattern=new RegExp("^[0123456789ABCDEF]{2,}$");
    return pattern.test(hex);
}

/**
 * <pre>This function checks if an input string has alphanumeric bits, i.e if they are made of 0-9 and A-Z.
 * <b>Alphabets have to be in Uppercase.</b></pre>
 * @param alphanumeric String that needs to be validated.
 * @return True if the String is a valid alphanumeric input, else false.
 */
function String_isAlphanumeric(alphanumeric){
    pattern=new RegExp("^[A-z0-9]+$");
    return pattern.test(alphanumeric);
}

/**
 * <pre>
 * This function checks if a String satisfies predetermined password parameters. The defaultSettings here are predetermined.
 * defaultSettings values are :
 * 1 if check for any 8 characters including alphabets, digits and symbols.
 * 2 if check for minimum 8 characters including minimum one uppercase and one digit.
 * 3 if check for minimum 8 characters including minimum one uppercase, digit, symbol and 8 characters.
 * </pre>
 * @param password String that needs to be validated.
 *
 * @param defaultSettings Can be 1, 2 or 3. Each are entitled to different parameters.
 * <pre>
 * 1 if check for any 8 characters including alphabets, digits and symbols.
 * 2 if check for minimum 8 characters including minimum one uppercase and one digit.
 * 3 if check for minimum 8 characters including minimum one uppercase, digit, symbol and 8 characters.
 * </pre>
 * @return True if the String is a valid word, else false.
 */
function String_isDefaultPassword(password, defaultSettings){
    pattern=null;
    switch(defaultSettings){
        case 1:  //8 characters any
            pattern=new RegExp("^[A-z0-9$@$!%*#?&]{8,}$");

            break;
        case 2: //minimum one uppercase and one digit, minimum 8 characters
            pattern=new RegExp("^(?=.*[A-Z])(?=.*[0-9])[A-z0-9$@$!%*#?&]{8,}$");
            break;
        case 3: //minimum one uppercase, digit, symbol and 8 characters
            pattern=new RegExp("^(?=.*[A-Z])(?=.*[0-9])(?=.*[$@$!%*#?&])[A-z0-9$@$!%*#?&]{8,}$");
            break;
        default: return false;
    }

    return pattern.test(password);
}

/**
 * This function checks for individual parameter validations, i.e it individually checks for alphabets, digits and symbols.
 * <pre>
 * Returns a boolean array of length 5. Each index has it's own significance. Default value is false.
 * Index 0 - Alphabet validation along with minimum alphabet length check.
 * Index 1 - Digit validation along with minimum digit length check.
 * Index 2 - Symbol validation along with minimum symbol length check.
 * Index 3 - Total minimum length check.
 * Index 4 - Total maximum length check.
 * Under any circumstances if pattern has characters that are not in the argument string, then index 0, 1, 2 will return false.
 * For example, if alphabets are "ABCdef", digits are "012" and symbol set is "$%" with minimum characters 2,1,1 respectively.
 * Then for pattern "ABC",
 * index 0 : true, index 1 : false, index 2: false.
 * for pattern "AB0"
 * index 0 : true, index 1 : true, index 2: false.
 * but for pattern "AB04", even if alphabet satisfies, but pattern as a whole is invalid hence output will be
 * index 0 : false, index 1 : false, index 2: false.
 * </pre>
 * @param password String that needs to be validated.
 * @param customPasswordFilter instance of CustomPasswordFilter.
 * @return True if the String is a valid word, else false.
 */
function String_isCustomPasswordParametric(password, customPasswordFilter){

    totalBuffer="[";
    minAlphabet="";
    minDigit="";
    minSymbol="";
    flagList=[];
    if(customPasswordFilter.alphabetSet!=null){


        minAlphabet=minAlphabet.concat("(?=.*["+customPasswordFilter.alphabetSet+"]{"+customPasswordFilter.minAlphabets+"})");
        totalBuffer=totalBuffer.concat(customPasswordFilter.alphabetSet);


    }




    if(customPasswordFilter.digitSet!=null){


        minDigit=minDigit.concat("(?=.*["+customPasswordFilter.digitSet+"]{"+customPasswordFilter.minDigits+"})");
        totalBuffer=totalBuffer.concat(customPasswordFilter.digitSet);

    }

   if(customPasswordFilter.symbolSet!=null){


        minSymbol=minSymbol.concat("(?=.*["+customPasswordFilter.symbolSet+"]{"+customPasswordFilter.minSymbols+"})");
        totalBuffer=totalBuffer.concat(customPasswordFilter.symbolSet);


    }




    if(customPasswordFilter.maxCharacter!=0){

        totalBuffer=totalBuffer.concat("]{"+customPasswordFilter.minCharacter+","+customPasswordFilter.maxCharacter+"}");

        if(password.length<=customPasswordFilter.maxCharacter)
            flagList[4]=true;
        else
            flagList[4]=false;
    }
    else{
        flagList[4]=false;
        totalBuffer=totalBuffer.concat("]{"+customPasswordFilter.minCharacter+",}");

    }



    if(password.length>=customPasswordFilter.minCharacter)
        flagList[3]=true;
    else
        flagList[3]=false;



    if(minAlphabet.length!=0){
        pattern=new RegExp("^"+minAlphabet+totalBuffer+"$");
        alert(minAlphabet+totalBuffer);
        flagList[0]=pattern.test(password);
        alert("alpha val : "+pattern.test(password));
    }
    else {

        flagList[0] = false;
    }

    if(minDigit.length!=0){
        pattern=new RegExp("^"+minDigit+totalBuffer+"$");
        flagList[1]=pattern.test(password);
    }
    else {

        flagList[1] = false;
    }
    if(minSymbol.length!=0){
        pattern=new RegExp("^"+minSymbol+totalBuffer+"$");
        flagList[2]=pattern.test(password);
    }
    else {

        flagList[2] = false;
    }

    return flagList;
}

/**
 * This function allows you to design custom password parameters.
 * @param password String that needs to be validated.
 * @param customPasswordFilter instance of CustomPasswordFilter.
 * @return True if the String is a valid word, else false.
 */
function String_isCustomPassword(password, customPasswordFilter){
    minBuffer="^";
    totalBuffer="[";
    if(customPasswordFilter.alphabetSet!=null){

        minBuffer=minBuffer.concat("(?=.*["+customPasswordFilter.alphabetSet+"]{"+customPasswordFilter.minAlphabets+"})");

        totalBuffer=totalBuffer.concat(""+customPasswordFilter.alphabetSet+"");

    }

    if(customPasswordFilter.digitSet!=null){
        minBuffer=minBuffer.concat("(?=.*["+customPasswordFilter.digitSet+"]{"+customPasswordFilter.minDigits+"})");
        totalBuffer=totalBuffer.concat(""+customPasswordFilter.digitSet+"");
    }

    if(customPasswordFilter.symbolSet!=null){
        minBuffer=minBuffer.concat("(?=.*["+customPasswordFilter.symbolSet+"]{"+customPasswordFilter.minSymbols+"})");
        totalBuffer=totalBuffer.concat(""+customPasswordFilter.symbolSet+"");
    }

    if(customPasswordFilter.maxCharacter!=0)
        totalBuffer=totalBuffer.concat("]{"+customPasswordFilter.minCharacter+","+customPasswordFilter.maxCharacter+"}$");
    else
        totalBuffer=totalBuffer.concat("]{"+customPasswordFilter.minCharacter+",}$");



    pattern= new RegExp(minBuffer+totalBuffer);
    return pattern.test(password);
}


function callError(errorNum){
  var errorCode = document.createElement("input");
  errorCode.setAttribute("type", "hidden");
  errorCode.setAttribute("name", "errorCode");
  errorCode.setAttribute("value",errorNum);

  var button = document.createElement("input");
  button.setAttribute('type', "submit");

  var myForm = document.createElement("form");
  myForm.setAttribute('method', "post");
  myForm.setAttribute('action', $('#hidden').val()+"/edunexus/ErrorController");
  myForm.appendChild(errorCode);
  myForm.appendChild(button);
  document.body.appendChild(myForm);
  myForm.submit();
}
