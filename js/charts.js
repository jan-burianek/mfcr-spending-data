/**
 *
 * @param url
 * @param options
 * @param chartCallback
 * @returns {{request: *}}
 * @constructor
 */
var Chart = function (url, options, chartCallback) {

	// Vytvoření requestu
	var xhr = $.ajax(url);

	// Zpracování dat
	var applyRequest = function (xhr, status) {

		// Zbavení se načítacího pacmana
		$('#' + options.element).css('background', 'none');

		// Naplnění grafu daty
		options['data'] = xhr;

		// Vytvoření grafu
		var chart = chartCallback(options);

		chart.on('click', function(i, row) {
			console.log(row);

			// updateEverything (row.label); // i.e. 2012
		});
	};

	xhr.done(applyRequest);

	return {
		'request': xhr
	}
}

/**
 *
 * @type {{formatMoney: Chart.format.formatMoney, shortenNumber: Chart.format.shortenNumber, format: Chart.format.format, formatDate: Chart.format.formatDate, formatMonth: Chart.format.formatMonth}}
 */
Chart.format = {
	formatMoney : function(n, c, d, t){
		var 	c = isNaN(c = Math.abs(c)) ? 2 : c,
			d = d == undefined ? "." : d,
			t = t == undefined ? "," : t,
			s = n < 0 ? "-" : "",
			i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "",
			j = (j = i.length) > 3 ? j % 3 : 0;
		return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "") + ' Kč';
	},

	shortenNumber : function (n) {
		var abbrs = ['', ' tis.', ' mil.', ' mld.', ' bil.'], i;

		for (i = 0; n > 1000; i++) {
			n = n/1000;
		}

		return Math.round(n) + abbrs[i] + ' Kč';
	},

	formatDate : function (x) {
		var date = new Date(x);
		var months = ['Leden', 'Únor', 'Březen', 'Duben', 'Květen', 'Červen', 'Červenec', 'Srpen', 'Září', 'Říjen', 'Listopad', 'Prosinec'];
		return months[date.getMonth()] + ' ' + date.getFullYear();
	},

	formatMonth : function (x) {
		var date = new Date(x);
		var months = ['Leden', 'Únor', 'Březen', 'Duben', 'Květen', 'Červen', 'Červenec', 'Srpen', 'Září', 'Říjen', 'Listopad', 'Prosinec'];
		return months[date.getMonth()];
	}
}

/**
 *
 * @param url
 * @param options
 * @constructor
 */
var LineChart = function (url, options) {

	// Podědění od Chart
	var chart = Chart.call(this, url, options, Morris.Line);
}

/**
 *
 * @param url
 * @param options
 * @constructor
 */
var AreaChart = function (url, options) {

	// Podědění od Chart
	var chart = Chart.call(this, url, options, Morris.Area);
}

/**
 *
 * @param url
 * @param options
 * @constructor
 */
var DonutChart = function (url, options) {

	// Podědění od Chart
	var chart = Chart.call(this, url, options, Morris.Donut);
}

/**
 * Úložiště a odkládací prostor konkrétních grafů
 *
 * @type {{comparsion: LineChart, donut: DonutChart}}
 */
var ChartStore = {

	// Nastaví víchozí hodnoty pro oblast grafu
	loadingState : function (chartArea) {

		// Destroy content
		chartArea.html('');

		// Alter styles
		chartArea.css('background-position', 'center center');
		chartArea.css('background-image', 'url(/images/ajax-loader.gif)');
		chartArea.css('background-repeat', 'no-repeat');
	},

	// ...
	comparsion : function() { return new LineChart('/homepage/get-comparsion', {
		'element': 'morris-area-chart',
		'xkey' : 'y',
		'ykeys' : [2010, 2011, 2012, 2013, 2014],
		'labels' : [2010, 2011, 2012, 2013, 2014],
		'xLabels' : 'month',
		'ymax' : 'auto',
		'xLabelAngle' :0,
		'dateFormat' : Chart.format.formatMonth,
		'xLabelFormat' : Chart.format.formatMonth,
		'yLabelFormat' : Chart.format.shortenNumber
	})},

	// ...
	donut : function() { return new DonutChart('/homepage/get-donut', {
		'element': 'morris-area-pie',
		'formatter' : Chart.format.shortenNumber
	})},

	// ...
	timeline : function() { return new AreaChart('/homepage/get-timeline', {
		'element': 'morris-area-chart',
		'xkey' : 'date',
		'ykeys' : ['amount'],
		'labels' : ['Částka'],
		'xLabels' : 'year',
		'ymax' : 'auto',
		'xLabelAngle' :0,
		'dateFormat' : Chart.format.formatDate,
		'xLabelFormat' : Chart.format.formatDate,
		'yLabelFormat' : Chart.format.shortenNumber
	})}
}

var Table = function (url, tableID, offset) {

	if (offset != null) {
		url += '?offset=' + offset;
	}

	// Vytvoření requestu
	var xhr = $.ajax(url);

	// Zpracování dat
	var applyRequest = function (xhr, status) {

		var table = $('#' + tableID);
		// Zbavení se načítacího pacmana
		table.css('background', 'none');

		// Nabití tabulky daty
		table.html(xhr);

		// TODO případně update dat atd.
	};

	xhr.done(applyRequest);

	return {
		'request': xhr
	}
}

var TopPartnersTable = function (url, tableID, paginator, offset) {
	var parent = Table.call(this, url, tableID, offset);

	$.extend(parent, {
		'paginator' : paginator
	});

	return parent;
}

/**
 *
 * @type {{loadingState: TableStore.loadingState}}
 */
var TableStore = {

	/**
	 *
	 */
	loadingState : function (table) {

		// Destroy content
		table.html('<tr><td class="loadable-area"></td></tr>');

		// Alter styles
		table.find('td')
			.css('height', '407px')
			.css('background-position', 'center center')
			.css('background-image', 'url(/images/ajax-loader.gif)')
			.css('background-repeat', 'no-repeat');
	},

	topPartnersPaginator : null,

	topPartners : function (offset) {

		if (this.topPartnersPaginator == null) {
			this.topPartnersPaginator = new Paginator(
				'top-partners-paginator',
				TableStore.topPartners
			);
		}

		var containerID = 'toppartners';

		TableStore.loadingState($('#' + containerID));

		return new TopPartnersTable (
			'/homepage/table-toppartners',
			containerID,
			this.topPartnersPaginator,
			offset || null
		)
	},

	timeTopPartnersPaginator : null,

	timeTopPartners : function (offset) {

		if (this.timeTopPartnersPaginator == null) {
			this.timeTopPartnersPaginator = new Paginator(
				'time-top-partners-paginator',
				TableStore.timeTopPartners
			);
		}

		var containerID = 'timetoppartners';

		TableStore.loadingState($('#' + containerID));

		return new TopPartnersTable (
			'/stats/table-toppartners?year=2011',
			containerID,
			this.topPartnersPaginator,
			offset || null
		)
	}
}

/**
 *
 * @param button
 * @returns {{enable: enable, disable: disable, makeActive: makeActive, makeInactive: makeInactive}}
 * @constructor
 */
var PaginatorButton = function (button, dataOffset) {

	var _button = button;

	var _dataOffset = dataOffset;

	this.setDataOffset = function (offset) {
		_button.find('a').attr('data-offset', offset)
	}

	this.setDataOffset(_dataOffset);

	this.enable = function ( ) {
		_button.removeClass('disabled');
	};

	this.disable = function ( ) {
		_button.addClass('disabled');
	};

	this.makeActive = function ( ) {
		_button.addClass('active');
	};

	this.makeInactive = function ( ) {
		_button.removeClass('active');
	};
}

/**
 *
 * @param id
 * @param callback
 * @param numFields
 * @param pageSize
 * @constructor
 */
var Paginator = function (id, callback, numFields, pageSize) {

	// Default values
	var
		_numFields = typeof numFields !== 'undefined' ? numFields : 7,
		_pageSize = typeof pageSize !== 'undefined' ? pageSize : 10,
		_currentPage = 0,
		_paginator = $('#' + id),
//
// TODO next and prev buttons
//		_prevButton = new PaginatorButton(_paginator.find('li.previous'), 0),
//		_nextButton = new PaginatorButton(_paginator.find('li.next'), _pageSize),
//
		_buttons = [];

	for (var i = 1; i <= _numFields; i++)
	{
		_buttons[i] = new PaginatorButton($(_paginator.find('li').get(i - 1)), (i - 1) * _pageSize);
	}

//
//	_prevButton.disable();
//

	/**
	 * Update pozice a tlačítek
	 *
	 * @param current
	 */
	var update = function (current) {
		current.closest('ul').find('li').removeClass('active');
		current.closest('li').addClass('active');

//
// TODO kontrola jestli další položka ještě existuje
//		var current_offset = parseInt(current.attr('data-offset'));
//		_nextButton.setDataOffset(current_offset + 10);
//
//		if (current_offset > 0) {
//			_prevButton.enable();
//			_prevButton.setDataOffset(current_offset - 10);
//		} else {
//			_prevButton.disable();
//		}
	}

	/**
	 *
	 */
	_paginator.find('a').click (function (e) {
		e.preventDefault();

		var a = $(this);

		callback(parseInt(a.attr('data-offset')));

		update(a);
	});
}