/**
 * EMThemes
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the framework to newer
 * versions in the future. If you wish to customize the framework for your
 * needs please refer to http://www.emthemes.com/ for more information.
 *
 * @category    EM
 * @package     EM_ThemeFramework
 * @copyright   Copyright (c) 2012 CodeSpot JSC. (http://www.emthemes.com/)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author      Giao L. Trinh (giao.trinh@emthemes.com)
 */

(function() {
var $ = jQuery;

var GRID_COL_WIDTH = 25;
var GRID_GUTTER_WIDTH = 5;
var GRID_COLS = 24;

EM_Framework = { };

EM_Framework.canvas = {
	init: function() {
		
		// make available blocks sortable
		$('.available_blocks').sortable({
			connectWith: '.grid_item_dropzone, .container_free'
		}).disableSelection();
		
		// create all available container blocks {{{
		var addedBlockFlags = {};
		for (var packageTheme in EM_Framework.THEME_BLOCKS) {
			for (var blockName in EM_Framework.THEME_BLOCKS[packageTheme]) {
				if (typeof addedBlockFlags[blockName] != 'undefined') continue;
				
				addedBlockFlags[blockName] = true;
				
				var blockLabel = EM_Framework.THEME_BLOCKS[packageTheme][blockName];
				$('.available_blocks').append(''
					+ '<div class="block_item">'
					+    blockLabel
					+ '  <input type="hidden" value="'+blockName+'" />'
					+ '</div>');
			}
		}
		
		this.onThemeChange($('#package_theme').val());
		// }}}
		
		
		$('.layout_canvas').sortable({
			items: '.container_24, .container_free'
		}).disableSelection();
		
		// create grids on canvas {{{
		for (var i = 0; i < EM_Framework.CANVAS_CONTENT.length; i++) {
			var containerData = EM_Framework.CANVAS_CONTENT[i];
			
			if (containerData.type == 'container_24') {
				// create grid and move blocks
				
				var containerDiv = this.createContainerDiv(containerData);
				for (var j = 0; j < containerData.items.length; j++) {
					// create clear div
					if (typeof containerData.items[j] == 'string' && containerData.items[j] == 'clear') {
						this.createClearDiv();
					}

					// create grid_* div
					else if (typeof containerData.items[j] == 'object') {
						var items = containerData.items[j].items;
						var $div = this.createGridDiv(containerData.items[j]);

						// move blocks from available blocks to the grid
						for (var k = 0; k < items.length; k++)
							this.moveBlockToGrid(items[k], $div);
					}

				}
			}
			else {
				// move blocks to container_free div
				var containerDiv = this.createFreeContainerDiv(containerData);
				for (var j = 0; j < containerData.items.length; j++) {
 					this.moveBlockToFreeContainer(containerData.items[j], containerDiv);
				}
			}
			
		}
		// }}}
		
		
		
		
	},
	
	moveBlockToGrid: function(blockName, gridDiv) {
		var blockDiv = $(".block_item:has(input[value='"+blockName+"'])");
		$(gridDiv).find('.grid_item_dropzone').append(blockDiv);
	},
	
	moveBlockToFreeContainer: function(blockName, containerDiv) {
		var blockDiv = $(".block_item:has(input[value='"+blockName+"'])");
		$(containerDiv).append(blockDiv);
	},
	
	createContainerDiv: function(data) {
		var self = this;
		
		$div = $(document.createElement('div'))
					.append(''
						+ '<div class="container-toolbar">'
						+ '  <button onclick="EM_Framework.canvas.deleteContainerDiv(this.parentNode.parentNode); return false;"><span><span><span></span>Delete</span></span></button>'
						+ '</div>')
					.addClass('container_24');
		$('.layout_canvas').append($div);
		$div.hide().fadeIn()
			.sortable({
				items: '.grid_item, .clear',
				connectWith: '.container_24'
			}).disableSelection()
			.click(function() {
				$('.container_24, .container_free').removeClass('selected');
				$(this).addClass('selected');
				
				self.populateContainerAttributeForm(this);
			});
			
		if (typeof data == 'object') {
			$div.data({
				custom_css: data.custom_css,
				inner_html: data.inner_html,
				outer_html: data.outer_html,
				display_empty: data.display_empty
			});
		}
		
		return $div;
	},
	
	createFreeContainerDiv: function(data) {
		var self = this;
		
		$div = $(document.createElement('div'))
					.append(''
						+ '<div class="container-toolbar">'
						+ '  <button onclick="EM_Framework.canvas.deleteFreeContainerDiv(this.parentNode.parentNode); return false;"><span><span><span></span>Delete</span></span></button>'
						+ '</div>')
					.addClass('container_free');
		$('.layout_canvas').append($div);
		$div.hide().fadeIn()
			.sortable({
				items: '.block_item',
				connectWith: '.grid_item_dropzone, .available_blocks'
			}).disableSelection()
			.click(function() {
				$('.container_24, .container_free').removeClass('selected');
				$(this).addClass('selected');
				
				self.populateContainerAttributeForm(this);
			});

		if (typeof data == 'object') {
			$div.data({
				custom_css: data.custom_css,
				inner_html: data.inner_html,
				outer_html: data.outer_html,
				display_empty: data.display_empty
			});
		}
		
		return $div;
	},
	
	createGridDiv: function(data) {
		var $div = $(document.createElement('div'))
					.append('' 
						+ '<div class="border">'
						+ '  <div class="toolbar">'
						+ '    <button onclick="EM_Framework.canvas.deleteGridDiv(this.parentNode.parentNode.parentNode); return false;"><span><span><span></span>Delete</span></span></button>'
						+ '  </div>'
						+ '  <div class="grid_item_dropzone"></div>'
						+ '</div>')
					.addClass('grid_item grid_'+GRID_COLS);
					

		$('.container_24:last').append($div);
		$div.hide().fadeIn(); // do fading effect
		
		if (typeof data == 'object') {
			if (data.column) $div.removeClass('grid_'+GRID_COLS).addClass('grid_'+data.column);
			if (data.push) $div.addClass('push_'+data.push);
			if (data.pull) $div.addClass('pull_'+data.pull);
			if (data.prefix) $div.addClass('prefix_'+data.prefix);
			if (data.suffix) $div.addClass('suffix_'+data.suffix);
			if (data.first) $div.addClass('alpha');
			if (data.last) $div.addClass('omega');
			$div.data({
				custom_css: data.custom_css,
				inner_html: data.inner_html,
				display_empty: data.display_empty
			});
		}
		
		this.applyGridItemEvents($div);
		
		return $div;
	},
	
	createClearDiv: function() {
		$div = $(document.createElement('div'))
				.append('<div class="toolbar"><button onclick="EM_Framework.canvas.deleteClearDiv(this.parentNode.parentNode); return false;"><span><span><span></span>Delete</span></span></button></div>')
				.addClass('clear');
		$('.container_24:last').append($div);
		$div.hide().fadeIn(); // do fading effect
		
		return $div;
	},
	
	applyGridItemEvents: function(grid_item) {
		var self = this;
		
		$('.grid_item_dropzone', grid_item).sortable({
			items: '.block_item',
			connectWith: '.grid_item_dropzone, .available_blocks, .container_free'
		}).disableSelection();

		$(grid_item)
			.resizable({
				grid: GRID_COL_WIDTH + GRID_GUTTER_WIDTH,
				containment: '.container_24',
				handles: "e",
				stop: function(event, ui) {

					ui.helper.attr('class', ui.helper.attr('class').replace(/\s?grid_[0-9]+/, ''))
							.addClass('grid_' + Math.round((ui.size.width + GRID_GUTTER_WIDTH) / (GRID_COL_WIDTH + GRID_GUTTER_WIDTH)))
							.attr('style', '');
				}
			})
			.disableSelection()
			.click(function(event) {
				$('.grid_item').removeClass('selected');
				$(this).addClass('selected');
				
				self.populateGridItemAttributeForm(this);
			});
	},
	
	deleteContainerDiv: function(div) {
		$(div).fadeOut(function() { 
			$('.available_blocks').append($(this).find('.block_item').fadeIn());
			$(this).remove(); 
		});
	},
	
	deleteFreeContainerDiv: function(div) {
		$(div).fadeOut(function() { 
			$('.available_blocks').append($(this).find('.block_item').fadeIn());
			$(this).remove(); 
		});
	},
	
	deleteGridDiv: function(div) {
		$(div).fadeOut(function() { 
			$('.available_blocks').append($(this).find('.block_item').fadeIn());
			$(this).remove(); 
		});
	},
	
	deleteClearDiv: function(div) {
		$(div).fadeOut(function() {
			$(this).remove();
		});
	},
	
	/**
	 * Populate Container Attributes Form
	 */
	populateContainerAttributeForm: function(container) {
		var o = this.getContainerAttributes(container);
		$('#container_attr_custom_css').val(o.custom_css);
		$('#container_attr_inner_html').val(o.inner_html);
		$('#container_attr_outer_html').val(o.outer_html);
		$('#container_attr_display_empty').attr('checked', o.display_empty ? 'checked' : false);
	},
	
	/**
	 * Get Container attributes
	 */
	getContainerAttributes: function(container) {
		var ret = { };
		
		ret.custom_css = $(container).data('custom_css') || '';
		ret.inner_html = $(container).data('inner_html') || '';
		ret.outer_html = $(container).data('outer_html') || '';
		ret.display_empty = $(container).data('display_empty') || false;
		
		return ret;
	},
	
	/**
	 * save Attributes to the selected Container
	 */
	applySelectedContainerAttributes: function() {
		$container = $('.container_24.selected, .container_free.selected');
		
		$container.data('custom_css', $('#container_attr_custom_css').val());			
		$container.data('inner_html', $('#container_attr_inner_html').val());
		$container.data('outer_html', $('#container_attr_outer_html').val());
		$container.data('display_empty', $('#container_attr_display_empty').attr('checked') ? true : false);
		
	},
	
	
	/**
	 * Populate Grid Item Attributes Form
	 */
	populateGridItemAttributeForm: function(grid_item) {
		var o = this.getGridItemAttributes(grid_item);
		$('#attr_column').val(o.column);
		$('#attr_push').val(o.push);
		$('#attr_pull').val(o.pull);
		$('#attr_suffix').val(o.suffix);
		$('#attr_prefix').val(o.prefix);
		$('#attr_first').attr('checked', o.first ? 'checked' : false);
		$('#attr_last').attr('checked', o.last ? 'checked' : false);
		$('#attr_custom_css').val(o.custom_css);
		$('#attr_inner_html').val(o.inner_html);
		$('#attr_display_empty').attr('checked', o.display_empty ? 'checked' : false);
	},
	
	/**
	 * Get Grid Item attributes
	 */
	getGridItemAttributes: function(grid_item) {
		var ret = { };
		
		var m = $(grid_item).attr('class').match(/grid_([0-9]+)/);
		ret.column = m ? m[1] : '';
		
		var m = $(grid_item).attr('class').match(/push_([0-9]+)/);
		ret.push = m ? m[1] : '';
		
		var m = $(grid_item).attr('class').match(/pull_([0-9]+)/);
		ret.pull = m ? m[1] : '';
		
		var m = $(grid_item).attr('class').match(/prefix_([0-9]+)/);
		ret.prefix = m ? m[1] : '';
		
		var m = $(grid_item).attr('class').match(/suffix_([0-9]+)/);
		ret.suffix = m ? m[1] : '';
		
		ret.first = $(grid_item).hasClass('alpha') ? true : false;
		
		ret.last = $(grid_item).hasClass('omega') ? true : false;
		
		ret.custom_css = $(grid_item).data('custom_css') || '';
		ret.inner_html = $(grid_item).data('inner_html') || '';
		ret.display_empty = $(grid_item).data('display_empty') || false;
		
		return ret;
	},
	
	/**
	 * save Attributes to the selected Grid Item
	 */
	applySelectedGridItemAttributes: function() {
		$grid_item = $('.grid_item.selected');
		
		$grid_item.attr('class', $grid_item.attr('class').replace(/(grid_|push_|pull_|prefix_|suffix_)[0-9]+|alpha|omega/g, '').replace(/\s{2,}/, ' '));

		var n;
		if (n = $('#attr_column').val()) $grid_item.addClass('grid_' + Math.max(1, Math.min(parseInt(n), GRID_COLS)));
		if (n = $('#attr_push').val()) $grid_item.addClass('push_' + Math.max(1, Math.min(parseInt(n), GRID_COLS)));
		if (n = $('#attr_pull').val()) $grid_item.addClass('pull_' + Math.max(1, Math.min(parseInt(n), GRID_COLS)));
		if (n = $('#attr_prefix').val()) $grid_item.addClass('prefix_' + Math.max(1, Math.min(parseInt(n), GRID_COLS)));
		if (n = $('#attr_suffix').val()) $grid_item.addClass('suffix_' + Math.max(1, Math.min(parseInt(n), GRID_COLS)));
		
		if ($('#attr_first').attr('checked')) $grid_item.addClass('alpha');
		if ($('#attr_last').attr('checked')) $grid_item.addClass('omega');

		$grid_item.data('custom_css', $('#attr_custom_css').val());
		$grid_item.data('inner_html', $('#attr_inner_html').val());
		$grid_item.data('display_empty', $('#attr_display_empty').attr('checked') ? true : false);
	},
	
	serializeData: function() {
		var self = this;
		
		var data = [];
		// TODO update
		$('.layout_canvas .container_24, .layout_canvas .container_free').each(function() {
			var container = self.getContainerAttributes(this);
			container.type = $(this).hasClass('container_24') ? 'container_24' : 'container_free';
			container.items = [];
			
			if (container.type == 'container_24') {
				$('.grid_item, .clear', this).each(function() {
					if ($(this).hasClass('grid_item')) {
						var gridData = self.getGridItemAttributes(this);
						gridData.items = [];
						$(this).find('.block_item:visible input').each(function() {
							gridData.items.push($(this).val());
						});
						
						container.items.push(gridData);
					}
					else if ($(this).hasClass('clear')) {
						container.items.push('clear');
					}
				
				});
			}
			else {
				$(this).find('.block_item:visible input').each(function() {
					container.items.push($(this).val());
				});
			}
			data.push(container);
		});
		return JSON.stringify(data);
	},
	
	onThemeChange: function(packageTheme) {
		$('.block_item').each(function() {
			var blockName = $('input', this).val();
			if (blockName != '' && typeof EM_Framework.THEME_BLOCKS[packageTheme][blockName] == 'undefined')
				$(this).fadeOut(); // hide
			else
				$(this).fadeIn(); // show
		})
	}
	
};


$(function() {
	EM_Framework.canvas.init();
	
});

})();