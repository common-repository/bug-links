<?php
/*
Plugin Name: Bug Links
Plugin URI: http://wordpress.org/extend/plugins/bug-links/
Description: Filters commonly used references to bug numbers into active links, with (currently hardcoded) support for numerous Free Software projects.
Version: 1.0
Author: Jeff Waugh
Author URI: http://perkypants.org/
*/

/*
Copyright (C) Jeff Waugh <http://perkypants.org/>

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

function bug_links_filter($text) {
	global $bug_links_trans, $bug_links_search, $bug_links_replace;
	if (!isset($bug_links_trans)) {
		// Took some of these from live.gnome.org/InterWiki
		$bug_links_trans = array(
			'gnome' => 'http://bugzilla.gnome.org/show_bug.cgi?id=',
			'ubuntu' => 'https://launchpad.net/bugs/',
			'launchpad' => 'https://launchpad.net/bugs/',
			'lp' => 'https://launchpad.net/bugs/',
			'fedora' => 'https://bugzilla.redhat.com/bugzilla/show_bug.cgi?id=',
			'red hat' => 'https://bugzilla.redhat.com/bugzilla/show_bug.cgi?id=',
			'redhat' => 'https://bugzilla.redhat.com/bugzilla/show_bug.cgi?id=',
			'rh' => 'https://bugzilla.redhat.com/bugzilla/show_bug.cgi?id=',
			'free desktop' => 'https://bugs.freedesktop.org/show_bug.cgi?id=',
			'freedesktop' => 'https://bugs.freedesktop.org/show_bug.cgi?id=',
			'fdo' => 'https://bugs.freedesktop.org/show_bug.cgi?id=',
			'mozilla' => 'https://bugzilla.mozilla.org/show_bug.cgi?id=',
			'ximian' => 'http://bugzilla.ximian.com/show_bug.cgi?id=',
			'novell' => 'https://bugzilla.novell.com/show_bug.cgi?id=',
			'suse' => 'https://bugzilla.novell.com/show_bug.cgi?id=',
			'opensuse' => 'https://bugzilla.novell.com/show_bug.cgi?id=',
			'debian' => 'http://bugs.debian.org/',
			'wordpress' => 'http://trac.wordpress.org/ticket/',
			'wordpress mu' => 'http://trac.mu.wordpress.org/ticket/',
			'wpmu' => 'http://trac.mu.wordpress.org/ticket/',
			// This catch-all MUST stay at the bottom!
			// Currently doesn't work because preg_replace all works on the
			// same string, and the match doesn't take existing anchors into
			// account. At some point, we'll fix this. :-)
			//'bug' => 'http://bugzilla.gnome.org/show_bug.cgi?id=',
		);

		foreach ( (array) $bug_links_trans as $bug => $url ) {
			// The regexp is more complicated than the smilies one, because
			// we want to be able to reference bugs without the whitespace.
			$bug_links_search[] = '/([\W\s]*|^)('.preg_quote($bug, '/').'( bug[ #]*| ?#)(\d+))([\W\s]*|$)/i';
			$bug_masked = htmlspecialchars(trim($bug), ENT_QUOTES);
			$bug_links_replace[] = " $1<a href='".$url."$4' class='bug-link bug-link-".sanitize_title($bug)."'>$2</a>$5";
		}
	}

	$anchor = false;
    $output = '';
	// Taken from convert_smilies, added stuff to ignore anchor content
	$textarr = preg_split("/(<.*>)/U", $text, -1, PREG_SPLIT_DELIM_CAPTURE); // capture the tags as well as in between
	$stop = count($textarr);// loop stuff 
	for ($i = 0; $i < $stop; $i++) { 
		$content = $textarr[$i];
		// <a> handling
		if (strncasecmp($content, '<a ', 3) == 0) {
			$anchor = true;
		} elseif (strncasecmp($content, '</a>', 4) == 0) {
			$anchor = false;
		}
		// filtering
		if (!$anchor && (strlen($content) > 0) && ('<' != $content{0})) { // If it's not a tag, or the last tag was an anchor
			$content = preg_replace($bug_links_search, $bug_links_replace, $content);
		} 
		$output .= $content;
	}
	return $output;
}
add_filter('the_content', 'bug_links_filter');
add_filter('the_excerpt', 'bug_links_filter');
add_filter('comment_text', 'bug_links_filter');
add_filter('comment_excerpt', 'bug_links_filter');

function bug_links_wp_head() {
	$blf = dirname(__FILE__);
	$css = trailingslashit(get_option('siteurl')) . trailingslashit(substr($blf, strpos($blf, 'wp-content'))) . 'bug-links.css';
	echo '<link rel="stylesheet" href="'.$css.'" type="text/css" media="screen" />';
}
add_action('wp_head', 'bug_links_wp_head');
?>
