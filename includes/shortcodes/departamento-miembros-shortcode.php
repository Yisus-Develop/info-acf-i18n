<?php




// [departamento_miembros]

function mostrar_miembros_departamento_shortcode( $atts ) {
    $post_id = get_the_ID();

    // Si no hay repeater ACF 'members' en el post (departamento), no hay nada que listar
    if ( ! have_rows( 'members', $post_id ) ) {
        return '';
    }

    ob_start();
    echo '<div class="departamento-miembros-wrapper">';

    while ( have_rows( 'members', $post_id ) ) {
        the_row();

        $member_post = get_sub_field( 'member' );   // ACF subfield (post_object)
        $position    = get_sub_field( 'position' ); // Posición dentro del depto (texto opcional)

        if ( ! $member_post ) {
            continue;
        }

        $member_id  = $member_post->ID;
        $name       = get_the_title( $member_id );
        $permalink  = get_permalink( $member_id );
        $thumbnail  = get_the_post_thumbnail( $member_id, 'medium', [
            'class' => 'miembro-thumb',
            'alt'   => esc_attr( $name ),
            'loading' => 'lazy',
        ] );

        // ACF del miembro
        $degree   = get_field( 'degree',   $member_id );   // corregido (antes leías 'position')
        $function = get_field( 'function', $member_id );
        $email    = get_field( 'email',    $member_id );
        $linkedin = get_field( 'linkedin', $member_id );

        // Línea de cargo: prioriza el "position" del repeater; si no, usa function + degree
        $linea_cargo = $position ? $position : trim( ($function ?: '') . ( $degree ? (' – ' . $degree) : '' ) );

        echo '<div class="miembro">';

            // Foto
            echo '<div class="miembro-foto">';
                echo '<a href="' . esc_url( $permalink ) . '">';
                    if ( $thumbnail ) {
                        echo $thumbnail;
                    } else {
                        echo '<div class="miembro-thumb --placeholder">' . esc_html( mb_strtoupper( mb_substr( $name, 0, 1 ) ) ) . '</div>';
                    }
                echo '</a>';
            echo '</div>';

            // Info
            echo '<div class="miembro-info">';
                echo '<h3 class="miembro-nome"><a href="' . esc_url( $permalink ) . '">' . esc_html( $name ) . '</a></h3>';

                if ( $linea_cargo ) {
                    echo '<p class="miembro-posicao">' . esc_html( $linea_cargo ) . '</p>';
                }

                // Botonera: email / linkedin / Cartão Digital (popup)
                echo '<div class="miembro-contato">';

                 /*   if ( $email ) {
                        echo '<a href="' . esc_url( 'mailto:' . $email ) . '" class="btn-contacto" aria-label="E-mail">';
                        echo    '<img src="https://dappin.pt/InnovPlant/wp-content/uploads/2025/06/mail.svg" alt="mail-icon" width="20" height="20">';
                        echo '</a>';
                    }

                    if ( $linkedin ) {
                        echo '<a href="' . esc_url( $linkedin ) . '" class="btn-linkedin" target="_blank" rel="noopener" aria-label="LinkedIn">';
                        echo    '<img src="https://dappin.pt/InnovPlant/wp-content/uploads/2025/06/linke.svg" alt="icon-linkedin" width="20" height="20">';
                        echo '</a>';
                    }*/

                    // Botón + popup dinámico (toma el ID del loop si no pasas id)
                    echo do_shortcode( '[member_contact_popup id="' . intval( $member_id ) . '" btn="Cartão Digital"]' );

                echo '</div>'; // .miembro-contato

            echo '</div>'; // .miembro-info

        echo '</div>'; // .miembro
    }

    echo '</div>'; // .departamento-miembros-wrapper

    return ob_get_clean();
}
add_shortcode( 'departamento_miembros', 'mostrar_miembros_departamento_shortcode' );




// [departamento_miembros]
/*
function mostrar_miembros_departamento_shortcode( $atts ) {
	ob_start();

	$post_id = get_the_ID();

	if ( have_rows( 'members', $post_id ) ) :
		echo '<div class="departamento-miembros-wrapper">';
		
		while ( have_rows( 'members', $post_id ) ) : the_row();
			$member_post = get_sub_field( 'member' );
			$position = get_sub_field( 'position' );

			if ( $member_post ) :
				$member_id = $member_post->ID;
				$thumbnail = get_the_post_thumbnail( $member_id, 'medium' );
				$name = get_the_title( $member_id );
				$degree = get_field( 'position', $member_id );
				$function = get_field( 'function', $member_id );
				$email = get_field( 'email', $member_id );
				$linkedin = get_field( 'linkedin', $member_id );
				$permalink = get_permalink( $member_id );
				?>
				
				<div class="miembro">
					<div class="miembro-foto">
						<a href="<?php echo esc_url( $permalink ); ?>">
							<?php echo $thumbnail; ?>
						</a>
					</div>
					<div class="miembro-info">
						<h3 class="miembro-nome">
							<a href="<?php echo esc_url( $permalink ); ?>">
								<?php echo esc_html( $name ); ?>
							</a>
						</h3>
						<?php if ( $position ) : ?>
							<p class="miembro-posicao"><?php echo esc_html( $position ); ?></p>
						<?php endif; ?>
						<div class="miembro-contato">
							<a href="<?php echo esc_url( 'mailto:' . $email); ?>" class="btn-contacto"> <img src="https://dappin.pt/InnovPlant/wp-content/uploads/2025/06/mail.svg" alt="mail-icon" width="20" height="NAN"> </a>
							<?php if ( $linkedin ) : ?>
								<a href="<?php echo esc_url( $linkedin ); ?>" class="btn-linkedin" target="_blank"><img src="https://dappin.pt/InnovPlant/wp-content/uploads/2025/06/linke.svg" alt="icon-linkedin" width="20" height="NAN"></a>
							<?php endif; ?>
						</div>
					</div>
				</div>

				<?php
			endif;
		endwhile;

		echo '</div>';
	endif;

	return ob_get_clean();
}
add_shortcode( 'departamento_miembros', 'mostrar_miembros_departamento_shortcode' );

*/