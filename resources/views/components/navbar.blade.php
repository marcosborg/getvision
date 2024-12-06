<nav id="navbar" class="navbar">
    <ul>
        <li><a class="nav-link active" href="{{ request()->is('/') ? '#hero' : '/' }}">Início</a></li>
        <li class="dropdown"><a href="#"><span>A empresa</span> <i class="bi bi-chevron-down"></i></a>
            <ul>
                <li><a href="/cms/4/quem-somos">Quem somos</a></li>
                <li><a href="/cms/5/o-nosso-compromisso">O nosso compromisso</a></li>
                <li><a href="/cms/6/carreiras"">Carreiras</a></li>
                <li><a href="/cms/7/parceiros">Parceiros</a></li>
                <li><a href="/cms/8/contatos">Contatos</a></li>
            </ul>
        </li>
        <li class="dropdown"><a href="#"><span>TVDE</span> <i class="bi bi-chevron-down"></i></a>
            <ul>
                <li><a href="/cms/9/trabalhar-com-viatura-propria">Trabalhar com Viatura Própria</a></li>
                <li><a href="/cms/10/motorista-tvde">Motorista TVDE</a></li>
                <li><a href="/cms/11/programa-de-apoio-aos-motoristas">Programa de Apoio Motoristas</a></li>
                <li><a href="/cms/12/consultoria-a-emtresa-tvde">Consultoria a Empresa TVDE</a></li>
                <li><a href="/cms/13/aluguer-viaturas">Aluguer Viaturas</a></li>
                <li><a href="/cms/14/formacao">Formação</a></li>
            </ul>
        </li>
        <li><a class="nav-link" href="/stand">Stand</a></li>
        <li><a class="nav-link" href="/transfers-tours">Transfers e Tours</a></li>
        <li><a class="nav-link" href="https://linktr.ee/expertcom.pt" target="_new">Fale connosco</a></li>
        @auth
        <li><a class="getstarted" href="/admin">Dashboard</a></li>
        @else
        <li><a class="getstarted" href="/login">Login</a></li>
        @endauth
    </ul>
    <i class="bi bi-list mobile-nav-toggle"></i>
</nav><!-- .navbar -->