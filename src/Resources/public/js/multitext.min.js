/**
 * Class MultiText
 *
 * Provide methods to handle back end tasks.
 * @copyright  Helmut Schottmüller 2008
 * @author     Helmut Schottmüller <typolight@aurealis.de>
 * @package    Backend
 */
var MultiText =
{
	/**
	 * Multitext wizard
	 * @param object
	 * @param string
	 * @param string
	 */
	multitextWizard: function(el, command, id)
	{
		var table = $(id);
		var thead = table.getFirst();
		var tbody = thead.getNext();
		var rows = tbody.getChildren();
		var parentTd = $(el).getParent();
		var parentTr = parentTd.getParent();
		var cols = parentTr.getChildren();
		var index = 0;
		var selectElement = null;
		for (var i=0; i<cols.length; i++)
		{
			if (cols[i] == parentTd)
			{
				break;
			}

			index++;
		}

		Backend.getScrollOffset();

		switch (command)
		{
			case 'rnew':
				var tr = new Element('tr');
				var childs = parentTr.getChildren();

				for (var i=0; i<childs.length; i++)
				{
					var next = childs[i].clone(true).inject(tr);
					if (!selectElement) selectElement = next.getFirst();
					next.getFirst().value = '';
				}
				tr.inject(parentTr, 'after');
				break;

			case 'rcopy':
				var tr = new Element('tr');
				var childs = parentTr.getChildren();

				for (var i=0; i<childs.length; i++)
				{
					var next = childs[i].clone(true).inject(tr);
					if (!selectElement) selectElement = next.getFirst();
					next.getFirst().value = childs[i].getFirst().value;
				}
				tr.inject(parentTr, 'after');
				break;

			case 'rup':
				parentTr.getPrevious() ? parentTr.inject(parentTr.getPrevious(), 'before') : parentTr.inject(tbody);
				break;

			case 'rdown':
				parentTr.getNext() ? parentTr.inject(parentTr.getNext(), 'after') : parentTr.inject(tbody.getFirst().getNext(), 'before');
				break;

			case 'rdelete':
				(rows.length > 1) ? parentTr.dispose() : null;
				break;
		}

		rows = tbody.getChildren();

		for (var i=0; i<rows.length; i++)
		{
			var childs = rows[i].getChildren();

			for (var j=0; j<childs.length; j++)
			{
				var first = childs[j].getFirst();
				if (first && first.type.toLowerCase() == 'text')
				{
					first.name = first.name.replace(/\[[0-9]+\][[0-9]+\]/ig, '[' + i + '][' + j + ']')
				}
			}
		}
		if (selectElement)
		{
			selectElement.select();
		}
	}
};