<footer class="footer">
    <div class="footer-content">
        <div class="footer-logo">
            <img src="{{ asset('assets/img/logos/jhire.png') }}" alt="JHIRE Motors">
        </div>

        <div class="footer-info">
            <p><strong>JHIRE MOTORS</strong> — Taller Multiservicio Automotriz Jhire</p>
            <p>
                <span class="material-symbols-outlined icon-footer">location_on</span>
                La Paz - Bolivia |
                <span class="material-symbols-outlined icon-footer">call</span>
                +591 699-94-555
            </p>
            <p>
                <span class="material-symbols-outlined icon-footer">mail</span>
                jhirmotors@gmail.com
            </p>
        </div>
                
        <div class="footer-social">
            <div class="footer-social-title">Redes Sociales</div>
            
            <div class="footer-icons">
                <a href="*" target="_blank" class="boton-red">
                    <img src="{{ asset('assets/img/social_media/logo_tiktok.png') }}" alt="TikTok">
                </a>
                <a href="*" target="_blank" class="boton-red">
                    <img src="{{ asset('assets/img/social_media/logo_instagram.png') }}" alt="Instagram">
                </a>
                <a href="*" target="_blank" class="boton-red">
                    <img src="{{ asset('assets/img/social_media/logo_facebook.png') }}" alt="Facebook">
                </a>
            </div>
        </div>
    </div>

    <div class="footer-credits">
        <p>© 2025 JHIRE MOTORS. Todos los derechos reservados.</p>
    </div>

    <style>
        /* === FOOTER UNIFICADO === */
        footer {
            position: relative;
            background: linear-gradient(180deg, var(--orange-2), var(--orange-1) 5%, #e35d00 100%);
            color: #fff;
            overflow: hidden;
            padding: 40px 60px 20px 60px;
        }

        /* ✨ Línea superior brillante tipo metálico */
        footer::before {
            content: "";
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            height: 5px;
            background: white;
            box-shadow:
                0 0 8px rgba(0, 0, 0, 1),
                0 0 15px rgba(0, 0, 0, 1),
                0 0 25px rgba(255, 255, 255, 0.85),
                0 0 50px rgba(0, 0, 0, 0.5);
            filter: blur(0.3px);
        }

        /* === CONTENIDO PRINCIPAL === */
        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            text-align: left;
            gap: 60px;
        }

        .footer-logo img {
            height: 80px;
            filter: drop-shadow(0 0 8px rgba(255, 255, 255, 0.4));
        }

        .footer-info {
            flex: 1;
            font-size: 15px;
            line-height: 1.6;
            color: #fff;
        }

        .footer-social {
            display: flex;
            flex-direction: column; /* 🔥 solo el título arriba, íconos abajo */
            align-items: center; /* centra todo */
            justify-content: center;
            gap: 10px;
        }
        
        /* Título */
        .footer-social-title {
            font-weight: 600;
            font-size: 16px;
            color: #fff;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        
        /* Contenedor de íconos */
        .footer-icons {
            display: flex; /* 🔥 los íconos en fila */
            justify-content: center;
            align-items: center;
            gap: 20px; /* espacio entre iconos */
        }

        .icon-footer {
            vertical-align: middle;
            font-size: 20px;
            color: #fff; /* blanco o el color que uses para texto */
            margin-right: 5px;
            margin-left: 5px;
        }

        .boton-red {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            padding: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 0.3s ease;
            box-shadow: 0 0 8px rgba(255, 255, 255, 0.2);
        }

        .boton-red:hover {
            background: rgba(30, 30, 30, 0.58);
            transform: scale(1);
            box-shadow:
                0 0 8px #000000ff,
                0 0 15px #393939ff,
                0 0 25px rgba(62, 62, 62, 0.7);
        }

        .boton-red img {
            width: 38px;
            height: 38px;
        }

        /* === CRÉDITOS INFERIORES === */
        .footer-credits {
            margin-top: 25px;
            text-align: center;
            font-size: 15px;
            opacity: 0.85;
            border-top: 3px solid rgba(0, 0, 0, 0.2);
            padding-top: 10px;
        }

        /* === RESPONSIVO === */
        @media (max-width: 768px) {
            footer {
                padding: 30px 20px;
            }

            .footer-content {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .footer-info {
                margin: 15px 0;
            }
        }
    </style>
</footer>
