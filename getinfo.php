<?php
/**
 * The GetInfo Plugin.
 *
 * @package GetInfo
 */

/**
 * Plugin Name: GetInfo
 * Description: Get info, do tests etc.
 * Version: 0.0.1
 * Author: Dekode
 * License: GPLv2 or later
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * List of domains we want to redirect to HTTPS.
 *
 * @var string[]
 */
$https_domains = [
	'22julisenteret.no',
	'ansvarlignaringsliv.no',
	'apenhetsutvalget.no',
	'arbeidstidsutvalget.no',
	'banklovkommisjonen.no',
	'beredskapssenter.regjeringen.no',
	'blankholmutvalget.no',
	'byerogdistrikter.no',
	'delingsokonomi.dep.no',
	'design.dep.no',
	'domstolkommisjonen.no',
	'eiti.no',
	'eosmidlene.regjeringen.no',
	'etikkradet.no',
	'familieportalen.mfa.no',
	'folkehelsemelding.regjeringen.no',
	'gronnkonkurransekraft.no',
	'helsekonferansen.no',
	'jobbifin.dep.no',
	'jobbifinans.dep.no',
	'klagenemndsekretariatet.no',
	'klagenemndssekretariatet.no',
	'kommunedata.regjeringen.no',
	'liedutvalget.no',
	'likestiltnorden2017.regjeringen.no',
	'marintmiljo.no',
	'nettsteder.regjeringen.no',
	'nygenerelldel.regjeringen.no',
	'open.regjeringa.no',
	'open.regjeringen.no',
	'opplaringslovutvalget.no',
	'produktivitetskommisjon.no',
	'produktivitetskommisjon.stat.no',
	'produktivitetskommisjonen.no',
	'produktivitetskommisjonen.stat.no',
	'responsiblebusiness.no',
	'samanom.no',
	'sammenom.no',
	'samvitsutvalet.no',
	'samvittighetsutvalget.no',
	'seedvault.no',
	'skm.stat.no',
	'sosentkokebok.regjeringen.no',
	'varslerutvalget.no',
	'varslingsutvalget.no',
	'www.22julisenteret.no',
	'www.ansvarlignaringsliv.no',
	'www.apenhetsutvalget.no',
	'www.arbeidstidsutvalget.no',
	'www.banklovkommisjonen.no',
	'www.beredskapssenter.regjeringen.no',
	'www.blankholmutvalget.no',
	'www.byerogdistrikter.no',
	'www.domstolkommisjonen.no',
	'www.eiti.no',
	'www.etikkradet.no',
	'www.gronnkonkurransekraft.no',
	'www.helsekonferansen.no',
	'www.jobbifinans.dep.no',
	'www.klagenemndsekretariatet.no',
	'www.klagenemndssekretariatet.no',
	'www.liedutvalget.no',
	'www.marintmiljo.no',
	'www.opplaringslovutvalget.no',
	'www.responsiblebusiness.no',
	'www.samanom.no',
	'www.sammenom.no',
	'www.samvitsutvalet.no',
	'www.samvittighetsutvalget.no',
	'www.seedvault.no',
	'www.varslerutvalget.no',
	'www.varslingsutvalget.no',
	'www.xn--ansvarlignringsliv-xub.no',
	'xn--ansvarlignringsliv-xub.no',
];

/*
 * If we’re not on HTTPS,
 * the requested hostname is in the list above
 * and the request method is GET or HEAD (we don't want to redirect e.g. POST as we’ll lose the data)
 * we do a redirect to the same URL but on HTTPS.
 */
if ( ! is_ssl() && in_array( $_SERVER['HTTP_HOST'], $https_domains, true ) && in_array( strtolower( $_SERVER['REQUEST_METHOD'] ), [ 'get', 'head' ], true ) ) {
	header( 'Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], true, 301 );
	exit;
}
