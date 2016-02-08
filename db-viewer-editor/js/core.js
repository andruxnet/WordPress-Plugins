function updateActions( index ) {
	jQuery( '#roles-permissions' ).datagrid( 'updateRow', {
		index: index,
		row: {}
	});
}

function getRowIndex( target ) {
	var tr = jQuery( target ).closest( 'tr.datagrid-row' );
	return parseInt( tr.attr( 'datagrid-row-index' ) );
}

function editrow( target ) {
	jQuery( '#roles-permissions' ).edatagrid( 'editRow', getRowIndex( target ) );
}

function saverow( target ) {
	jQuery( '#roles-permissions' ).datagrid( 'endEdit', getRowIndex( target ) );
}

function cancelrow( target ) {
	jQuery( '#roles-permissions' ).datagrid( 'cancelEdit', getRowIndex( target ) );
}

jQuery( document ).ready( function( $ ) {
	/* add a new query to the database */
	$( document ).on( 'click', '#new-db-query', function() {
		$( '#dialog-queries' ).dialog( 'open' ).dialog( 'setTitle', 'New DB Query' );
		$( '#fm-queries' ).form( 'clear' ).find( '#dlg-rows' ).val( '10' );
		url = ajaxurl + '?action=settings_add_query';
		getRolePermissions();
	});

	/* edit/update a query in the database */
	$( document ).on( 'click', '#edit-db-query', function() {
		var row = $( '#query-list' ).datagrid( 'getSelected' );

		if ( row ) {
			$( '#dialog-queries' ).dialog( 'open' ).dialog( 'setTitle', 'Edit DB Query');
			$( '#fm-queries' ).form( 'load', row );
			url = ajaxurl + '?action=settings_update_query&id=' + row.id;
			getRolePermissions( true );
		}
	});

	/* remove a query from the database */
	$( document ).on( 'click', '#remove-db-query', function() {
		var row = $( '#query-list' ).datagrid( 'getSelected' );

		if ( row ) {
			$.messager.confirm( 'Confirm', 'Are you sure you want to remove this DB Query?', function( r ) {
				if ( r ) {
					$.post( ajaxurl, { action: 'settings_remove_query', id: row.id }, function( result ) {
						if ( result.success ) {
							$( '#query-list' ).datagrid( 'reload' );
						}
						else {
							$.messager.show({
								title: 'Error',
								msg: result.errorMsg
							});
						}
					}, 'json' );
				}
			});
		}
	});

	/* open a dialog box and show the results of testing a query */
	$( document ).on( 'click', '#dlg-test', function() {
		var query = $( '#dlg-query' ).val();

		$( '#win' ).window({
			title: 'DB Query Tester',
			width: 800,
			height: 600,
			maximizable: false,
			minimizable: false,
			modal: true
		});

		$( '#win' ).window('refresh', ajaxurl + '?action=settings_test_query&query=' + query );
		$( '#win' ).window( 'center' );
	});

	/* save changes from the edit query dialog box */
	$( document ).on( 'click', '#dlg-save', function() {
		$( '#fm-queries' ).form( 'submit', {
			url: url,
			onSubmit: function() {
				return $( this ).form( 'validate' );
			},
			success: function( result ) {
				var result = eval( '(' + result + ')' );
				if ( result.errorMsg ) {
					$.messager.show({
						title: 'Error',
						msg: result.errorMsg
					});
				}
				else {
					$( '#dialog-queries' ).dialog( 'close' );
					$( '#query-list' ).datagrid( 'reload' );
				}
			}
		});
	});

	/* close the edit query dialog box without saving */
	$( document ).on( 'click', '#dlg-cancel', function() {
		$( '#dialog-queries' ).dialog( 'close' );
	});

	/* manage editing role permissions datagrid */
	function getRolePermissions( edit ) {
		var queryId = ( edit === undefined ) ? '0' : $( '#query-list' ).datagrid( 'getSelected' ).id;

		$( '#roles-permissions' ).edatagrid({
			title: 'Role Permissions',
			url: ajaxurl + '?action=settings_get_roles&query_id=' + queryId,
			updateUrl: ajaxurl + '?action=settings_update_role&query_id=' + queryId,
			iconCls: 'icon-edit',
			width: 420,
			height: 240,
			singleSelect: true,
			idField: 'role',
			columns:[[
				{field: 'role', title: 'Role', width: 100},
				{field: 'view', title: 'View', width: 50, align: 'center',
					editor: {
						type: 'checkbox',
						options: {
							on: 'yes',
							off: 'no'
						}
					}
				},
				{field: 'edit', title: 'Edit', width: 50, align: 'center',
					editor: {
						type: 'checkbox',
						options: {
							on: 'yes',
							off: 'no'
						}
					}
				},
				{field: 'remove', title: 'Remove', width: 50, align: 'center',
					editor: {
						type: 'checkbox',
						options: {
							on: 'yes',
							off: 'no'
						}
					}
				},
				{field: 'action', title: 'Action', width: 80, align: 'center',
					formatter: function( value, row, index ) {
						if ( row.editing ) {
							var s = '<a class="icon-save" style="text-decoration: none; padding: 0px 5px;" href="#" onclick="saverow( this )">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a> ';
							var c = '<a class="icon-cancel" style="text-decoration: none; padding: 0px 5px;" href="#" onclick="cancelrow( this )">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>';
							return s + c;
						}
						else {
							var e = '<a class="icon-edit" style="text-decoration: none; padding: 0px 5px;" href="#" onclick="editrow(this)">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a> ';
							return e;
						}
					}
				}
			]],
			onBeforeEdit: function( index, row ) {
				row.editing = true;
				updateActions( index );
			},
			onAfterEdit: function( index, row ) {
				row.editing = false;
				updateActions( index );
			},
			onCancelEdit: function( index, row ) {
				row.editing = false;
				updateActions( index );
			},
			onEdit: function( index, row ) {
				var view = $( this ).datagrid( 'getEditor', { index: index, field: 'view' });
				var edit = $( this ).datagrid( 'getEditor', { index: index, field: 'edit' });
				var remove = $( this ).datagrid( 'getEditor', { index: index, field: 'remove' });

				$( this ).datagrid( 'beginEdit', index );

				$( view.target ).click( function() {
					if ( ! $( this ).is( ':checked' ) ) {
						$( edit.target ).prop( 'checked', false );
						$( remove.target ).prop( 'checked', false );
					}
				});

				$( edit.target ).click( function() {
					if ( $( this ).is( ':checked' ) ) {
						$( view.target ).prop( 'checked', true );
					}
					else {
						$( remove.target ).prop( 'checked', false );
					}
				});

				$( remove.target ).click( function() {
					if ( $( this ).is( ':checked' ) ) {
						$( view.target ).prop( 'checked', true );
						$( edit.target ).prop( 'checked', true );
					}
				});
			}
		});
	}
});