<?php include_once 'views/template/header-principal.php';
include_once 'views/template/portada.php'; ?>

<!-- Start Contact Area -->
<section class="main-contact-area contact-info-area contact-info-three pt-100 pb-70">
    <div class="container">
        <div class="section-title">
            <span>Contactos</span>
            <h2>Si tienes dudas envianos un mensaje via Whatsapp</h2>
            <p></p>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="contact-wrap contact-pages">
                    <div class="contact-form contact-form-mb">
                        <?php if (!empty($_GET['msg'])) {
                            if ($_GET['msg'] == 1) {
                                echo '<div class="alert alert-info alert-dismissible fade show" role="alert">
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    <strong>Aviso!</strong> Correo Enviado.
                                </div>';
                            }else{
                                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                <strong>Aviso!</strong> Error al enviar correo: '.$_GET['msg'].'
                            </div>';
                            }
                        ?>

                        <?php } ?>

                        <!-- Nuevo Formulario Contacto vía WhatsApp -->
<form id="contactForm" novalidate>
    <div class="row">
        <div class="col-lg-6 col-sm-6">
            <div class="form-group">
                <input type="text" name="name" id="name" class="form-control" placeholder="Su nombre">
                <div class="invalid-feedback" id="nameError"></div>
            </div>
        </div>
        <div class="col-lg-6 col-sm-6">
            <div class="form-group">
                <input type="email" name="email" id="email" class="form-control" placeholder="Tu correo electrónico">
                <div class="invalid-feedback" id="emailError"></div>
            </div>
        </div>
        <div class="col-lg-6 col-sm-6">
            <div class="form-group">
                <input type="text" name="phone_number" id="phone_number" class="form-control" placeholder="Su teléfono" maxlength="10">
                <div class="invalid-feedback" id="phoneError"></div>
            </div>
        </div>
        <div class="col-lg-6 col-sm-6">
            <div class="form-group">
                <input type="text" name="msg_subject" id="msg_subject" class="form-control" placeholder="Tu asunto">
                <div class="invalid-feedback" id="subjectError"></div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12">
            <div class="form-group">
                <textarea name="message" class="form-control textarea-hight" id="message" cols="30" rows="4" placeholder="Tu mensaje"></textarea>
                <div class="invalid-feedback" id="messageError"></div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12">
            <button type="submit" class="default-btn btn-two">
                Enviar por WhatsApp
                <i class="flaticon-right"></i>
            </button>
        </div>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('contactForm').addEventListener('submit', function(e){
        e.preventDefault();

        // Limpiar mensajes y clases
        let fields = [
            {id: 'name', error: 'nameError'},
            {id: 'email', error: 'emailError'},
            {id: 'phone_number', error: 'phoneError'},
            {id: 'msg_subject', error: 'subjectError'},
            {id: 'message', error: 'messageError'}
        ];
        fields.forEach(f => {
            document.getElementById(f.id).classList.remove('is-invalid');
            document.getElementById(f.error).textContent = '';
        });

        // Obtener valores y limpiar espacios
        var nombre = document.getElementById('name').value.trim();
        var email = document.getElementById('email').value.trim();
        var telefono = document.getElementById('phone_number').value.trim();
        var asunto = document.getElementById('msg_subject').value.trim();
        var mensaje = document.getElementById('message').value.trim();

        var valid = true;

        // Validar nombre
        if (!nombre) {
            document.getElementById('name').classList.add('is-invalid');
            document.getElementById('nameError').textContent = 'Debes rellenar este campo';
            valid = false;
        }

        // Validar email tipo gmail
        var gmailRegex = /^[a-zA-Z0-9._%+-]+@gmail\.com$/;
        if (!email) {
            document.getElementById('email').classList.add('is-invalid');
            document.getElementById('emailError').textContent = 'Debes rellenar este campo';
            valid = false;
        } else if (!gmailRegex.test(email)) {
            document.getElementById('email').classList.add('is-invalid');
            document.getElementById('emailError').textContent = 'Debes ingresar un correo tipo @gmail.com';
            valid = false;
        }

        // Validar teléfono
        var phoneRegex = /^[0-9]{10}$/;
        if (!telefono) {
            document.getElementById('phone_number').classList.add('is-invalid');
            document.getElementById('phoneError').textContent = 'Debes rellenar este campo';
            valid = false;
        } else if (!phoneRegex.test(telefono)) {
            document.getElementById('phone_number').classList.add('is-invalid');
            document.getElementById('phoneError').textContent = 'Debes ingresar un número de 10 dígitos';
            valid = false;
        }

        // Validar asunto
        if (!asunto) {
            document.getElementById('msg_subject').classList.add('is-invalid');
            document.getElementById('subjectError').textContent = 'Debes rellenar este campo';
            valid = false;
        }

        // Validar mensaje
        if (!mensaje) {
            document.getElementById('message').classList.add('is-invalid');
            document.getElementById('messageError').textContent = 'Debes rellenar este campo';
            valid = false;
        }

        // Si todo está válido, abrir WhatsApp con datos
        if (valid) {
            var numero = '573104204487'; // WhatsApp Colombia
            var texto = 
                "Nombre: " + nombre + "\n" +
                "Email: " + email + "\n" +
                "Teléfono: " + telefono + "\n" +
                "Asunto: " + asunto + "\n" +
                "Mensaje: " + mensaje;
            window.open("https://wa.me/" + numero + "?text=" + encodeURIComponent(texto), "_blank");
        }
    });
});
</script>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row">
                    <div class="col-lg-6 col-sm-6">
                        <div class="single-contact-info">
                            <i class="bx bx-envelope"></i>
                            <h3>Correo Electrónico:</h3>
                            <a href="mailto:<?php echo $data['empresa']['correo']; ?>"><?php echo $data['empresa']['correo']; ?></a>
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6">
                        <div class="single-contact-info">
                            <i class="bx bx-phone-call"></i>
                            <h3>Teléfono:</h3>
                            <a href="">Tel. + <?php echo $data['empresa']['telefono']; ?></a>
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6">
                        <div class="single-contact-info">
                            <i class="bx bx-location-plus"></i>
                            <h3>Dirección</h3>
                            <a href="#"><?php echo $data['empresa']['direccion']; ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Contact Area -->

<!-- Start Map Area -->
<div class="map-area">
<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3976.713372862167!2d-75.5783081!3d4.645127700000001!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8e388c338d843183%3A0x96120060502385ea!2sEl%20Rancho%20de%20Salento!5e0!3m2!1ses!2sco!4v1756497343047!5m2!1ses!2sco" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
</div>
<!-- End Map Area -->

<?php include_once 'views/template/footer-principal.php'; ?>

</body>

</html>