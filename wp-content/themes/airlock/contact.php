<?php
/**
Template Name: Contact
 * The template for displaying Contact form.
 *
 */
get_header(); ?>
	<?php get_sidebar(); ?>
	<div id="content">
	<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
		<?php //echo '<h1 class="page-title mm">' . get_the_title() . '</h1>'; ?>
			
		<div id="post-<?php the_ID(); ?>" <?php post_class('contact-page'); ?>>
			<div class="about-text">
				<strong>A propos</strong>
				
				<p>MG (www.moteur-graphique.com) a été créé en janvier 2013 à Paris.</p>
				<p></p>
				<p>Véritable plateforme d’échanges entre les créatifs et les designers, MG propose les services suivants :</p>
				<ul>
					<li>Informations sur écoles d’art et de design</li>
					<li>Offres d’emploi dans le domaine du graphisme et du design</li>
					<li>Regroupement des meilleurs designs, créations graphiques et web</li>
					<li>Concours de design</li>
				</ul>
				<p>Nous souhaitons promouvoir le partage d’œuvres originales entre les designers et artistes français, faire évoluer la communauté du design en France, ainsi que devenir un intermédiaire entre les designers et les entreprises afin d’être un vecteur de lien et de collaboration entre les deux parties.</p>
				
				<p>MG a pour ambition de partager un maximum de création alimentant l’évolution de notre société.</p>
				
				<strong>Exonération de la responsabilité</strong>
				
				<p>MG est une plateforme d’échange qui a pour objet de permettre la diffusion, le partage et l’échange d’œuvre de designers et d’artistes. Tous les éléments publiés sur le présent site proviennent de collections ou partages de ces derniers.</p>
				
				<p>Toute personne estimant une violation de la part de MG d’un droit dont il serait titulaire ou d’une reproduction sans le consentement de son auteur a la possibilité de le signaler à MG (conformément à l’article 6-1-5 de la loi du 21 juin 2004 n°2004-575) par courrier recommandé avec accusé de réception ou par email, précisant l’ensemble des informations suivantes :</p>
				
				<p>L’identité du notifiant : s’il s’agit d’une personne physique : nom, prénom, date de naissance, nationalité, domicile et profession. S’il s’agit d’une personne morale : sa forme, sa dénomination sociale, son siège social et l’organe qui la représente légalement ;
				
				<p>La description des faits litigieux et leur localisation précise sur www.moteur-graphique.com ;</p>
				
				<p>Les motifs pour lesquels le contenu doit être retiré comprenant la mention des dispositions légales applicables ;</p>
				
				<p>Le Membre est informé si MG dispose de la possibilité de retirer, conformément à la loi, toute information ou contenu, ou d’en rendre l’accès impossible, et ce dès lors qu’elle prend connaissance de leur caractère manifestement illicite.</p>
				
				<p>Cette violation sera susceptible de donner lieu à des poursuites judiciaires menées par son éditeur à MG et ses ayants droits.</p>
				
				&nbsp;
			</div>
			<?php echo $apollo13->contact_form( $apollo13->get_option( 'settings', 'contact_email' ) ); ?>
			<div class="text">
				<?php the_content(); ?>
			</div>
			
			<div class="clear"></div>
		</div>
	
	<?php endwhile; // end of the loop. ?>
	</div>		
<?php get_footer(); ?>