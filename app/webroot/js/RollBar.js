function RollBar ( strRollBarId, strListener ) {
  this.arrRolls = new Array ();
  this.strListener = strListener;
  this.strRollBarId = strRollBarId;
  this.strRollBarCss = '';
  this.strRollDirect = 'UP';
  this.blnRollStop = false;
  this.intPauseTime = 180;
  this.intInterval = 25;
  this.intStopTime = 0;
  this.intMaxEdge = 120;
  this.intPreEdge = 0;
  this.intCurrentEdge = 0;

  this.addListener = roll_listener;
  this.load = roll_load;
  this.rollUp = roll_up;
  this.startRoll = roll_start;
  this.stopRoll = roll_stop;
  this.show = roll_show;
  this.getObject = get_object;
}

function roll_listener ( strListener) {
  this.strListener = strListener;
}

function roll_load ( strRoll ) {
  this.arrRolls.push ( strRoll );
}

function roll_start () {
  this.show ();

  if ( this.strRollDirect == 'UP' ) {
    var marqueesH = this.intMaxEdge + "px";
    var objRoll = this.getObject ( this.strRollBarId );
    with ( objRoll ) {
      style.height = marqueesH;
      style.overflow = "hidden";
      noWrap = true;
      scrollTop = 0;
    }
    
    var strFunc = this.strListener + ".blnRollStop = true";
    objRoll.onmouseover = new Function ( strFunc );

    strFunc = this.strListener + ".blnRollStop = false";
    objRoll.onmouseout = new Function ( strFunc );

    objRoll.innerHTML += objRoll.innerHTML;
    
    strFunc = this.strListener + ".rollUp ()";
    setInterval ( strFunc, this.intInterval );
  }
}

function roll_stop () {
  this.blnRollStop = true;
}

function roll_show () {
  var strRollBar = "<div id='" + this.strRollBarId + "' class='" + 
                    this.strRollBarCss + "'><table style='width:100%;' cellspacing=0 cellpadding=0>";
  
  for ( index in this.arrRolls ) {
    strRollBar += "<tr><td>" + this.arrRolls [ index ] + "</td></tr>";
  }
  
  strRollBar += "</table></div>";
  document.write ( strRollBar );
}

function roll_up () {
  if ( ! this.blnRollStop ) {
    this.intCurrentEdge += 1;
    if ( this.intCurrentEdge == this.intMaxEdge + 1 ) {
      this.intStopTime += 1;
      this.intCurrentEdge -= 1;
      if ( this.intStopTime == this.intPauseTime ) {
        this.intCurrentEdge = 0;
        this.intStopTime = 0;
      }
    }
    else {
      var objRoll = this.getObject ( this.strRollBarId );
      if ( objRoll != null ) {
        this.intPreEdge = objRoll.scrollTop; 
        objRoll.scrollTop += 1;
        if ( this.intPreEdge == objRoll.scrollTop ) { 
          objRoll.scrollTop = this.intMaxEdge;
	  objRoll.scrollTop += 1;
        }
      }
    }
  }
}

function get_object ( objName ) {
  if ( document.getElementById ) {
    return eval ( 'document.getElementById("' + objName + '")' );
  }
  else if ( document.layers ) {
    return eval ( "document.layers['" + objName +"']" );
  }
  else {
    return eval ( 'document.all.' + objName );
  }
}
