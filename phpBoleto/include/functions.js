<!--
function isWhitespace(s)
{
  var whitespace = " \t\n\r";

  if (s.length == 0) {
    // empty field!
    return true;
  } else {
    // check for whitespace now!
    for (var z = 0; z < s.length; z++) {
      // Check that current character isn't whitespace.
      var c = s.charAt(z);
      if (whitespace.indexOf(c) == -1) return false;
    }
    return true;
  }
}

function isEmail(s)
{
  // email text field.
  var sLength = s.length;
  var denied_chars = new Array(" ", "\n", "\t", "\r", "%", "$", "#", "!", "~", "`", "^", "&", "*", "(", ")", "=", "+", "{", "}", "[", "]", ",", ";", ":", "'", "\"", "?", "<", ">", "/", "\\", "|");

  // look for @
  if (s.indexOf("@") == -1) return false;

  // look for more than one @ sign
  if (s.indexOf("@") != s.lastIndexOf("@")) return false;

  // look for any special character
  for (var z = 0; z < denied_chars.length; z++) {
    if (s.indexOf(denied_chars[z]) != -1) return false;
  }

  // look for .
  if (s.indexOf(".") == -1) return false;

  // no two dots alongside each other
  if (s.indexOf("..") != -1) return false;

  // the last character cannot be a .
  if ((s.charAt(sLength-1) == ".") || (s.charAt(sLength-1) == "_")) return false;

  return true;
}

function isNumberOnly(s)
{
  var check = parseFloat(s).toString();
  if ((s.length == check.length) && (check != "NaN")) {
      return true;
  } else {
      return false;
  }
}
//-->
