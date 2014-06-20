
/* Customized Countdown Settings */

var triangles_trigger = false;

/* Countdown */
var triangles_date = trian.countdown_date;
triangles_date.setMonth(triangles_date.getMonth() - 1);

jQuery('.triangles #countdown_clock').circularCountdown({
	strokeDaysBackgroundColor: convertHex(trian.primary_color,100),
	strokeDaysColor: convertHex(trian.secondary_color,100),
	strokeHoursBackgroundColor: convertHex(trian.primary_color,100),
	strokeHoursColor: convertHex(trian.secondary_color,100),
	strokeMinutesBackgroundColor: convertHex(trian.primary_color,100),
	strokeMinutesColor: convertHex(trian.secondary_color,100),
	strokeSecondsBackgroundColor: convertHex(trian.primary_color,100),
	strokeSecondsColor: convertHex(trian.secondary_color,100),
	strokeWidth:4,
	strokeBackgroundWidth:2,
	countdownEasing:'easeOutBounce',
	countdownTickSpeed:'slow',
	backgroundShadowColor: 'rgba(0,0,0,0.2)',
	backgroundShadowBlur: 0,
	strokeShadowColor: 'rgba(0,0,0,0.2)',
	strokeShadowBlur: 0,
	countdownDate: triangles_date
});
	
