import React, { Component } from 'react'
import utility from '../../../scripts/df_scripts/utilities'
import './style.css'

export default class TableOfContents extends Component {
	static slug = 'difl_table_of_contents'
	et_utils = window.ET_Builder.API.Utils

	constructor( props ) {
		super( props );
		this.wrapperRef = React.createRef();
	}

	static css( props, moduleInfo ) {
		const { generateStyles } = window.ET_Builder.API.Utils
		const additionalCss = []
		const icons = [
			{ key: 'expand_icon', selector: '%%order_class%% .heading_container .icon .expand_icon.et-pb-icon' },
			{ key: 'collapse_icon', selector: '%%order_class%% .heading_container .icon .collapse_icon.et-pb-icon' },
			{ key: 'single_icon', selector: '%%order_class%% .heading_container .icon.single_icon .et-pb-icon' },
		]
		const spaces = [ 'margin', 'padding' ]
		const styles = [
			{
				name: 'header_bg_color',
				selector: '%%order_class%% .heading_container',
				cssProperty: 'background-color',
			},
			{
				name: 'expand_icon_color',
				selector: '%%order_class%% .heading_container .expand_icon.et-pb-icon',
				cssProperty: 'color',
			},
			{
				name: 'collapse_icon_color',
				selector: '%%order_class%% .heading_container .collapse_icon.et-pb-icon',
				cssProperty: 'color',
			},
			{
				name: 'single_icon_color',
				selector: '%%order_class%% .heading_container .icon.single_icon .et-pb-icon',
				cssProperty: 'color',
			},
			{
				name: 'expand_icon_size',
				selector: '%%order_class%% .heading_container .expand_icon.et-pb-icon',
				cssProperty: 'font-size',
			},
			{
				name: 'collapse_icon_size',
				selector: '%%order_class%% .heading_container .collapse_icon.et-pb-icon',
				cssProperty: 'font-size',
			},
			{
				name: 'single_icon_size',
				selector: '%%order_class%% .heading_container .icon.single_icon .et-pb-icon',
				cssProperty: 'font-size',
			},
			{
				name: 'title_icon_heading_space',
				selector: '%%order_class%% .heading_container',
				cssProperty: 'gap',
			},
			{
				name: 'active_link_color',
				selector: '%%order_class%% .difl_toc_main_container .body_container ul.difl--toc--ul a.active',
				cssProperty: 'color',
			},
			{
				name: 'title_icon_gap',
				selector: '.difl_table_of_contents .difl_toc_main_container .heading_container',
				cssProperty: 'gap',
			},
			{
				name: 'body_bg_color',
				selector: '%%order_class%% .difl_toc_main_container .body_container',
				cssProperty: 'background-color',
			},
		];

		if ( 'on' === props.use_content_height ) {
			styles.push( {
				name: 'content_height',
				selector: '%%order_class%% .difl_toc_main_container .body_container',
				cssProperty: 'height',
				important: true,
			} );
		}
		const headings = [ 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' ];

		headings.forEach( heading => {

			const icon = `marker_icon_${ heading }`;
			const size = `marker_icon_size_${ heading }`;
			const color = `marker_icon_color_${ heading }`;
			const level = heading.replace( 'h', '' );
			const selector = `.difl_table_of_contents .body_container.icon .difl_heading_level_${ level } > li > span.et-pb-icon`;
			styles.push( {
				name: size,
				selector,
				cssProperty: 'font-size',
			}, );
			styles.push( {
				name: color,
				selector,
				cssProperty: 'color',
			}, );

			icons.push( {
				key: icon,
				selector
			} );

			styles.push( {
				name: `marker_icon_space_heading_${ heading }`,
				selector: `.difl_table_of_contents .body_container.icon .difl_heading_level_${ level } > li > a`,
				cssProperty: 'margin-left'
			} );

			spaces.forEach( type => {
				const key = `heading_spacing_${ heading }_${ type }`;
				utility.process_margin_padding( {
					props,
					key,
					additionalCss,
					selector: `.difl_table_of_contents .body_container.icon .difl_heading_level_${ level } > li`,
					type,
				} )
			} )
		} );

		styles.forEach( item => {
			const args = Object.assign( { attrs: props }, item )
			additionalCss.push( generateStyles( args ) )
		} )
		spaces.forEach( type => {
			const key = `header_spacing_${ type }`
			utility.process_margin_padding( {
				props,
				key,
				additionalCss,
				selector: '%%order_class%% .heading_container',
				type,
			} )
		} )
		spaces.forEach( type => {
			const key = `content_spacing_${ type }`
			utility.process_margin_padding( {
				props,
				key,
				additionalCss,
				selector: '%%order_class%% .body_container',
				type,
			} )
		} )
		icons.forEach( ( { key, selector } ) => {
			utility.process_icon_font_style( {
				'props': props,
				'additionalCss': additionalCss,
				key,
				selector
			} );
		} )

		additionalCss.push( [ {
			selector: '%%order_class%% ul.difl--toc--ul',
			declaration: 'padding-bottom:0'
		} ] )

		additionalCss.push( [ {
			selector: '%%order_class%% .heading_container .icon .expand_icon.et-pb-icon',
			declaration: 'display:inline-block'
		} ] )

		additionalCss.push( [ {
			selector: '%%order_class%% .heading_container .icon .collapse_icon.et-pb-icon',
			declaration: 'display:inline-block'
		} ] )

		return additionalCss;
	}

	componentDidUpdate( prevProps, prevState, snapshot ) {
		this.get_generated_toc();
	}

	componentDidMount() {
		this.get_generated_toc();
	}

	render_title_icon() {
		const props = this.props;
		const is_title_icon = props.title_icon === 'on';
		if ( ! is_title_icon ) {
			return null;
		}
		const expand_icon = <span
			className="et-pb-icon expand_icon">{ this.et_utils.processFontIcon(
			props.expand_icon ) }</span>
		const collapse_icon = <span
			className="et-pb-icon collapse_icon">{ this.et_utils.processFontIcon(
			props.collapse_icon ) }</span>
		return (
			<div className="icon">{ expand_icon }{ collapse_icon }</div>
		)
	}

	get_all_settings() {
		const props = this.props;

		const allowedSettings = [
			'heading_tags',
			'minimum_number_of_headings',
			'scrolling_speed',
			'content_height',
			'default_collapse_state',
			'offset',
			'marker_type',
			'collapsible_with_sticky',
		];

		const headings = [ 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' ];

		headings.forEach( ( heading ) => {
			allowedSettings.push( `marker_icon_${ heading }` );
			allowedSettings.push( `marker_icon_size_${ heading }` );
			allowedSettings.push( `expand_icon_color_${ heading }` );
		} );

		const settings = {};

		allowedSettings.forEach( ( key ) => {
			settings[key] = props[key];
		} );

		settings['offset_tablet'] = props['offset_tablet'];
		settings['offset_phone'] = props['offset_phone'];

		return settings;
	}


	render() {
		const props = this.props;

		const marker_type = undefined === props.marker_type ? 'number' : props.marker_type;
		const height_enable = 'on' === props.use_content_height ? ' height_enable' : '';
		const hierarchical_view = 'off' === props.hierarchical_view ? ' non_hierarchical' : '';
		const collapse_sticky = 'on' === props.collapsible_toc && 'on' === props.collapsible_with_sticky ? 'collapse_sticky' : '';
		const full_width_header = 'on' === props.full_width_header && 'on' === props.full_width_header ? 'full_width_header' : '';
		const collapse_icon_only = 'on' === props.collapse_icon_only && 'on' === props.collapse_icon_only ? 'collapse_icon_only' : '';
		const collapse_state = props.default_collapse_state


		const TitleTag = undefined === props.title_tag ? 'div' : props.title_tag;
		const heading = props.heading_tags.split( '|' ).map( ( value, index ) => {
			const key = `h${ ++index }`;
			return { [key]: value };
		} ).filter( val => val[Object.keys( val )[0]] !== '' ).filter( value => Object.values( value )[0] === 'on' );

		const list = [];

		if ( heading.length ) {
			//class name difl_heading_level_${index} in builder keep it simple i.e static but the markup should same
			// Make it accessible thus it will get SEO impression i.e role=group, role=treeitem etc
			list.push( <ul className={ `difl--toc--ul difl_heading_level_1` }>
				<li  { ...('icon' === props.marker_type ? {
					'data-icon': this.et_utils.processFontIcon(
						props.marker_icon_h1 )
				} : {}) }>
					<a className="difl--toc--anchor" href="#">Heading Level One</a>
				</li>
				<li  { ...('icon' === props.marker_type ? {
					'data-icon': this.et_utils.processFontIcon(
						props.marker_icon_h1 )
				} : {}) }>
					<a className="difl--toc--anchor" href="#">Heading Level One</a>
				</li>
				<li  { ...('icon' === props.marker_type ? {
					'data-icon': this.et_utils.processFontIcon( props.marker_icon_h1 ),
					// 'data-fm': this.et_utils.processIconFontData( props.marker_icon_h1 ).iconFontFamily,
					// 'data-fw': this.et_utils.processIconFontData( props.marker_icon_h1 ).iconFontWeight
				} : {}) }>
					<a className="difl--toc--anchor" href="#">Heading One</a>
				</li>
				<ul className={ `difl_heading_level_2` }>
					<li>Heading Level Two</li>
					<li>Heading Two</li>
					<ul className={ `difl_heading_level_3` }>
						<li>Heading Level Two</li>
						<li>Heading Two</li>
					</ul>
				</ul>
			</ul> );
			list.push( <ul className={ `difl_heading_level_1` }>
				<li>Heading Level</li>
				<li>Heading One</li>
				<ul className={ `difl_heading_level_2` }>
					<li>Heading Level Two</li>
					<li>Heading Two</li>
					<ul className={ `difl_heading_level_3` }>
						<li>Heading Level Two</li>
						<li>Heading Two</li>
					</ul>
				</ul>
			</ul> )
		}

		const post_content = this.get_generated_toc();

		return (<div ref={ this.wrapperRef }
					 className={ `difl_toc_main_container ${ collapse_state } ${ full_width_header } ${ collapse_icon_only }` }>
				<div className="heading_container">
					<TitleTag
						className="title">{ props.dynamic.title.hasValue ? utility._renderDynamicContent( props, 'title' ) : null }</TitleTag>
					{ this.render_title_icon() }
				</div>
				{
					post_content === null ? <div
							className={ `body_container ${ marker_type }  ${ height_enable }  ${ hierarchical_view } ${ collapse_sticky }` }>{ list }</div> :
						<div
							className={ `body_container ${ marker_type }  ${ height_enable }  ${ hierarchical_view } ${ collapse_sticky }` }
							dangerouslySetInnerHTML={ { __html: post_content } }></div>
				}
			</div>
		)
	}

	create_toc( text, level, parent = undefined ) {
		return {
			text,
			level,
			id: undefined,
			parent,
			children: []
		};
	}

	parse_headings( elements ) {
		const toc = [];
		let current_level = 0;
		let current_parent = undefined;

		elements.forEach( ( element, index ) => {
			const level = parseInt( element.tagName.substring( 1 ) );
			const text = element.textContent.trim();

			if ( current_level < level ) {
				const entry = this.create_toc( text, level, current_parent );
				current_parent ? current_parent.children.push( entry ) : toc.push( entry );
				current_parent = entry;
			} else {
				let new_parent = this.create_toc( text, level );
				while ( current_parent && current_parent.level >= level ) {
					current_parent = current_parent.parent;
				}
				if ( current_parent ) {
					new_parent.parent = current_parent;
					current_parent.children.push( new_parent );
				} else {
					toc.push( new_parent );
				}
				current_parent = new_parent;
			}

			current_level = level;

			const id = 'difl-toc-' + text.replace( /[^\w\s-]/g, '' ).replace( /\s+/g, '-' ).toLowerCase() + '-' + index;
			element.id = id;
			current_parent.id = id;
		} );

		return toc;
	}

	generate_markup( toc ) {
		const is_icon_marker = 'icon' === this.get_settings( 'marker_type' );
		let icon_span = '';

		const build_list = ( entries, depth = 1 ) => {
			let html = `<ul class="difl--toc--ul difl_heading_level_${ depth }">`;
			const icon_settings = this.get_settings( `marker_icon_h${ depth }` );

			entries.forEach( ( entry ) => {
				const classes = `difl--toc--li difl_heading_li_level_${ entry.level }`;
				const anchor = `<a class="difl--toc--anchor ${ classes }" href="#${ entry.id }">${ entry.text }</a>`;
				if ( is_icon_marker && undefined !== icon_settings ) {
					const icon = icon_settings.split( '||' )[0];
					icon_span = `<span class="et-pb-icon marker-icon">${ icon }</span>`;
				}
				html += `<li id="${ entry.id }-toc-li">${ icon_span } ${ anchor }`;
				if ( entry.children.length ) {
					html += build_list( entry.children, depth + 1 );
				}
				html += `</li>`;
			} );
			return html + `</ul>`;
		}

		return build_list( toc );
	}

	get_settings( key = 'all' ) {
		const settings = this.get_all_settings();
		if ( 'all' === key ) {
			return settings;
		}

		return settings[key] || '';
	}

	is_closest( element, selectors ) {
		if ( ! selectors ) return false;
		return selectors.split( ',' ).some( ( selector ) => element.closest( selector ) );
	}

	is_matched( element, selectors ) {
		if ( ! selectors ) return false;
		return selectors.split( ',' ).some( ( selector ) => element.matches( selector ) );
	}

	is_hidden( element ) {
		const style = window.getComputedStyle( element );
		return style.display === 'none' || style.visibility === 'hidden';
	}

	get_generated_toc() {
		const settings = this.get_settings();
		const heading_tags = settings.heading_tags.split( '|' );
		const module_hide_number = settings.minimum_number_of_headings;
		const container_exclude = this.get_settings( 'container_exclude_by_class' );
		const heading_exclude = this.get_settings( 'headings_exclude_by_class' );
		let main_content = document.querySelector( '#main-content' );
		const allowed_tags = [ 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' ].filter( ( tag, index ) => heading_tags[index] === 'on' );

		const templateString = `<div id="main-content">
				<div id="level-1">
				  <h1>Level 1 Heading</h1>
				  <div>
					<h2>Level 1 - H2</h2>
					<h3>Level 1 - H3</h3>
					<h4>Level 1 - H4</h4>
					<h5>Level 1 - H5</h5>
					<h6>Level 1 - H6</h6>
				  </div>
				</div>
				
				<div id="level-2">
				  <h1>Level 2 Heading</h1>
				  <div>
					<h2>Level 2 - H2</h2>
					<h3>Level 2 - H3</h3>
					<h4>Level 2 - H4</h4>
					<h5>Level 2 - H5</h5>
					<h6>Level 2 - H6</h6>
				  </div>
				</div>
				
				<div id="level-3">
				  <h1>Level 3 Heading</h1>
				</div>
				
				<div id="level-4">
				  <h1>Level 4 Heading</h1>
				  <div>
					<h2>Level 4 - H2</h2>
					<h3>Level 4 - H3</h3>
					<h4>Level 4 - H4</h4>
					<h5>Level 4 - H5</h5>
					<h6>Level 4 - H6</h6>
					</div>
				</div>
				</div>`;
		const parser = new DOMParser();
		const doc = parser.parseFromString( templateString, 'text/html' );
		if ( this.is_in_theme_builder() ) {
			main_content = doc.querySelector( '#main-content' );
		}

		if ( ! main_content ) return;


		if ( ! allowed_tags.length ) {
			return '';
		}

		let headings = [ ...main_content.querySelectorAll( allowed_tags.join( ',' ) ) ].filter( heading => {
			return ! (heading.closest( '.entry-title' ) || heading.closest( '.heading_container' ) || heading.closest( '#sidebar' ) || heading.closest( '#comment-wrap' ) || this.is_closest( heading, container_exclude ) || this.is_matched( heading, heading_exclude ) || this.is_hidden( heading ))
		} )

		if ( ! headings.length ) {
			main_content = doc.querySelector( '#main-content' );
			headings = [ ...main_content.querySelectorAll( allowed_tags.join( ',' ) ) ].filter( heading => {
				return ! (heading.closest( '.entry-title' ) || heading.closest( '.heading_container' ) || heading.closest( '#sidebar' ) || heading.closest( '#comment-wrap' ) || this.is_closest( heading, container_exclude ) || this.is_matched( heading, heading_exclude ) || this.is_hidden( heading ))
			} )
		}

		// if ( headings.length < module_hide_number ) {
		// 	this.toc_main.remove();
		// 	return;
		// }
		let parsed_toc = this.parse_headings( Array.from( headings ) );

		if ( parsed_toc.length === 0 ) {
			return null;
		}
		return this.generate_markup( parsed_toc );
	}

	is_in_theme_builder() {
		const url = new URL( window.location.href );
		const params = new URLSearchParams( url.search );
		return params.get( 'et_tb' );
	};

	is_in_builder() {
		const url = new URL( window.location.href );
		const params = new URLSearchParams( url.search );
		return params.get( 'et_fb' );
	};
}