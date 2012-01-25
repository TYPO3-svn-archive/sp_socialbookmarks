/*********************************************************************
 *  Copyright notice
 *
 *  (c) 2012 Kai Vogel <kai.vogel@speedprogs.de>, Speedprogs.de
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published
 *  by the Free Software Foundation; either version 3 of the License,
 *  or (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ********************************************************************/

Ext.ns('Socialbookmarks');


/****************************************************
 * Chart object
 ****************************************************/
Socialbookmarks.Charts = {
	init: function(){
		var data = [['Ext JS',115000],['jQuery',250100],['Prototype',150000],['mootools',75000],['YUI',95000],['Dojo',20000],['Sizzle',15000]];

		var store = new Ext.data.ArrayStore({
			fields:[{name:'framework'}, {name:'users', type:'float'}]
		});
		store.loadData(data);

		var pieChart = new Ext.chart.PieChart({
			store: store,
			dataField: 'users',
			categoryField : 'framework',
			background: {
				color: '#F8F8F8'
			},
		});

		var main = new Ext.Panel({
			layout: 'fit',
			renderTo: 'socialbookmarks-container',
			width: 450,
			frame: false,
			border: false,
			plain: false,
			bodyStyle:{'background': '#F8F8F8'},
			defaults: {
				height: 250,
				collapsible: false,
				border: false,
				titleCollapse: false,
				autoScroll: false
			},
			items:[pieChart],
		});
	}
}

Ext.onReady(Socialbookmarks.Charts.init, Socialbookmarks.Charts);