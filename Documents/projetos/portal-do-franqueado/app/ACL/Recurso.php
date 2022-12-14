<?php

namespace App\ACL;

use Illuminate\Database\Eloquent\Model;

/**
 * App\ACL\Recurso.
 *
 * @property int $id
 * @property string $descricao
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ACL\Grupo[] $grupos
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ACL\Recurso whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ACL\Recurso whereDescricao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ACL\Recurso whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ACL\Recurso whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Recurso extends Model
{
    const SUPER_ADMIN = 1;

    const PUB_CANAL_FRANQUEADO = 2;
    const PUB_COMUNICADOS = 3;
    const PUB_ARQUIVOS = 4;
    const PUB_SOLICITACOES = 5;
    const PUB_PEDIDOS = 6;
    const PUB_ENQUETES = 7;
    const PUB_BUSCA = 76;
    const PUB_CLIENTE_LOJA_LISTAR = 108;
    const PUB_CLIENTE_LOJA_CRIAR = 109;
    const PUB_CLIENTE_LOJA_EDITAR = 110;
    const PUB_CLIENTE_LOJA_DELETAR = 111;
    const PUB_METAS = 114;

    const PUB_CLIENTE_LOJA = [
        self::PUB_CLIENTE_LOJA_LISTAR,
        self::PUB_CLIENTE_LOJA_CRIAR,
        self::PUB_CLIENTE_LOJA_EDITAR,
        self::PUB_CLIENTE_LOJA_DELETAR,
    ];

    const ADM_VER_MENU = 8;

    const ADM_COMUNICADOS_LISTAR = 9;
    const ADM_COMUNICADOS_CRIAR = 10;
    const ADM_COMUNICADOS_EDITAR = 11;
    const ADM_COMUNICADOS_DELETAR = 12;
    const ADM_COMUNICADOS_VER_DESTINATARIO = 13;

    const ADM_COMUNICADOS = [
        self::ADM_COMUNICADOS_LISTAR,
        self::ADM_COMUNICADOS_CRIAR,
        self::ADM_COMUNICADOS_EDITAR,
        self::ADM_COMUNICADOS_DELETAR,
        self::ADM_COMUNICADOS_VER_DESTINATARIO,
    ];

    const ADM_PASTAS_LISTAR = 14;
    const ADM_PASTAS_CRIAR = 15;
    const ADM_PASTAS_EDITAR = 16;
    const ADM_PASTAS_DELETAR = 17;
    const ADM_PASTAS_ARQUIVOS_REMANEJAR = 18;
    const ADM_PASTAS_ARQUIVOS_DELETAR = 19;

    const ADM_PASTAS = [
        self::ADM_PASTAS_LISTAR,
        self::ADM_PASTAS_CRIAR,
        self::ADM_PASTAS_EDITAR,
        self::ADM_PASTAS_DELETAR,
        self::ADM_PASTAS_ARQUIVOS_REMANEJAR,
        self::ADM_PASTAS_ARQUIVOS_DELETAR,
    ];

    const ADM_ARQUIVOS_LISTAR = 20;
    const ADM_ARQUIVOS_CRIAR = 21;
    const ADM_ARQUIVOS_EDITAR = 22;
    const ADM_ARQUIVOS_DELETAR = 23;
    const ADM_ARQUIVOS_VER_DOWNLOADS = 24;
    const ADM_ARQUIVOS_VER_URL = 25;

    const ADM_ARQUIVOS = [
        self::ADM_ARQUIVOS_LISTAR,
        self::ADM_ARQUIVOS_CRIAR,
        self::ADM_ARQUIVOS_EDITAR,
        self::ADM_ARQUIVOS_DELETAR,
        self::ADM_ARQUIVOS_VER_DOWNLOADS,
        self::ADM_ARQUIVOS_VER_URL,
    ];

    const ADM_VITRINES_LISTAR = 26;
    const ADM_VITRINES_CRIAR = 27;
    const ADM_VITRINES_EDITAR = 28;
    const ADM_VITRINES_DELETAR = 29;

    const ADM_VITRINES = [
        self::ADM_VITRINES_LISTAR,
        self::ADM_VITRINES_CRIAR,
        self::ADM_VITRINES_EDITAR,
        self::ADM_VITRINES_DELETAR,
    ];

    const ADM_SETORES_LISTAR = 30;
    const ADM_SETORES_CRIAR = 31;
    const ADM_SETORES_EDITAR = 32;
    const ADM_SETORES_DELETAR = 33;
    const ADM_SETORES_REMANEJAR_SOLICITACOES = 34;
    const ADM_SETORES_DELETAR_SOLICITACOES = 77;

    const ADM_SETORES = [
        self::ADM_SETORES_LISTAR,
        self::ADM_SETORES_CRIAR,
        self::ADM_SETORES_EDITAR,
        self::ADM_SETORES_DELETAR,
        self::ADM_SETORES_REMANEJAR_SOLICITACOES,
        self::ADM_SETORES_DELETAR_SOLICITACOES,
    ];

    const ADM_SOLICITACOES_LISTAR = 35;
    const ADM_SOLICITACOES_CRIAR = 36;
    const ADM_SOLICITACOES_EDITAR = 37;
    const ADM_SOLICITACOES_DELETAR = 79;

    const ADM_SOLICITACOES = [
        self::ADM_SOLICITACOES_LISTAR,
        self::ADM_SOLICITACOES_CRIAR,
        self::ADM_SOLICITACOES_EDITAR,
        self::ADM_SOLICITACOES_DELETAR,
    ];

    const ADM_TAGS_LISTAR = 38;
    const ADM_TAGS_CRIAR = 39;
    const ADM_TAGS_EDITAR = 40;
    const ADM_TAGS_DELETAR = 41;
    const ADM_TAGS_VIDEOS_REMANEJAR = 42;
    const ADM_TAGS_VIDEOS_DELETAR = 43;

    const ADM_TAGS = [
        self::ADM_TAGS_LISTAR,
        self::ADM_TAGS_CRIAR,
        self::ADM_TAGS_EDITAR,
        self::ADM_TAGS_DELETAR,
        self::ADM_TAGS_VIDEOS_REMANEJAR,
        self::ADM_TAGS_VIDEOS_DELETAR,
    ];

    const ADM_VIDEOS_LISTAR = 44;
    const ADM_VIDEOS_CRIAR = 45;
    const ADM_VIDEOS_EDITAR = 46;
    const ADM_VIDEOS_DELETAR = 47;
    const ADM_VIDEOS_VER_QUEM_ASSISTIU = 48;

    const ADM_VIDEOS = [
        self::ADM_VIDEOS_LISTAR,
        self::ADM_VIDEOS_CRIAR,
        self::ADM_VIDEOS_EDITAR,
        self::ADM_VIDEOS_DELETAR,
        self::ADM_VIDEOS_VER_QUEM_ASSISTIU,
    ];

    const ADM_USUARIOS_LISTAR = 49;
    const ADM_USUARIOS_CRIAR = 50;
    const ADM_USUARIOS_EDITAR = 51;

    const ADM_USUARIOS = [
        self::ADM_USUARIOS_LISTAR,
        self::ADM_USUARIOS_CRIAR,
        self::ADM_USUARIOS_EDITAR,
    ];

    const ADM_PRACAS_LISTAR = 52;
    const ADM_PRACAS_CRIAR = 53;
    const ADM_PRACAS_EDITAR = 54;

    const ADM_PRACAS = [
        self::ADM_PRACAS_LISTAR,
        self::ADM_PRACAS_CRIAR,
        self::ADM_PRACAS_EDITAR,
    ];

    const ADM_LOJAS_LISTAR = 55;
    const ADM_LOJAS_CRIAR = 56;
    const ADM_LOJAS_EDITAR = 57;

    const ADM_LOJAS = [
        self::ADM_LOJAS_LISTAR,
        self::ADM_LOJAS_CRIAR,
        self::ADM_LOJAS_EDITAR,
    ];

    const ADM_CATEGORIAS_LISTAR = 58;
    const ADM_CATEGORIAS_CRIAR = 59;
    const ADM_CATEGORIAS_EDITAR = 60;

    const ADM_CATEGORIAS = [
        self::ADM_CATEGORIAS_LISTAR,
        self::ADM_CATEGORIAS_CRIAR,
        self::ADM_CATEGORIAS_EDITAR,
    ];

    const ADM_PRODUTOS_LISTAR = 61;
    const ADM_PRODUTOS_CRIAR = 62;
    const ADM_PRODUTOS_EDITAR = 63;

    const ADM_PRODUTOS = [
        self::ADM_PRODUTOS_LISTAR,
        self::ADM_PRODUTOS_CRIAR,
        self::ADM_PRODUTOS_EDITAR,
    ];

    const ADM_FORNECIMENTO_PEDIDOS_LISTAR = 64;
    const ADM_FORNECIMENTO_PEDIDOS_EDITAR = 65;
    const ADM_FORNECIMENTO_PEDIDOS_EXTRATO = 66;
    const ADM_FORNECIMENTO_PEDIDOS_DELETAR = 67;
    const ADM_FORNECIMENTO_PEDIDOS_MULTA_EDITAR = 113;

    const ADM_FORNECIMENTO = [
        self::ADM_FORNECIMENTO_PEDIDOS_LISTAR,
        self::ADM_FORNECIMENTO_PEDIDOS_EDITAR,
        self::ADM_FORNECIMENTO_PEDIDOS_EXTRATO,
        self::ADM_FORNECIMENTO_PEDIDOS_DELETAR,
        self::ADM_FORNECIMENTO_PEDIDOS_MULTA_EDITAR,
    ];

    const ADM_ENQUETES_LISTAR = 68;
    const ADM_ENQUETES_CRIAR = 69;
    const ADM_ENQUETES_DELETAR = 70;
    const ADM_ENQUETES_VER_RESULTADOS = 71;

    const ADM_ENQUETES = [
        self::ADM_ENQUETES_LISTAR,
        self::ADM_ENQUETES_CRIAR,
        self::ADM_ENQUETES_DELETAR,
        self::ADM_ENQUETES_VER_RESULTADOS,
    ];

    const ADM_DOWNLOAD_MAILLING = 72;

    const ADM_GRUPOS_LISTAR = 73;
    const ADM_GRUPOS_CRIAR = 74;
    const ADM_GRUPOS_EDITAR = 75;
    const ADM_GRUPOS_DELETAR = 78;

    const ADM_GRUPOS = [
        self::ADM_GRUPOS_LISTAR,
        self::ADM_GRUPOS_CRIAR,
        self::ADM_GRUPOS_EDITAR,
        self::ADM_GRUPOS_DELETAR,
    ];

    const ADM_AVALIADOR_OCULTO_USUARIOS_LISTAR = 80;
    const ADM_AVALIADOR_OCULTO_USUARIOS_CRIAR = 81;
    const ADM_AVALIADOR_OCULTO_USUARIOS_EDITAR = 82;
    const ADM_AVALIADOR_OCULTO_USUARIOS_DELETAR = 83;

    const ADM_AVALIADOR_OCULTO_USUARIOS = [
        self::ADM_AVALIADOR_OCULTO_USUARIOS_LISTAR,
        self::ADM_AVALIADOR_OCULTO_USUARIOS_CRIAR,
        self::ADM_AVALIADOR_OCULTO_USUARIOS_EDITAR,
        self::ADM_AVALIADOR_OCULTO_USUARIOS_DELETAR,
    ];

    const ADM_AVALIADOR_OCULTO_FORMULARIOS_LISTAR = 84;
    const ADM_AVALIADOR_OCULTO_FORMULARIOS_CRIAR = 85;
    const ADM_AVALIADOR_OCULTO_FORMULARIOS_EDITAR = 86;
    const ADM_AVALIADOR_OCULTO_FORMULARIOS_DELETAR = 87;

    const ADM_AVALIADOR_OCULTO_FORMULARIOS = [
        self::ADM_AVALIADOR_OCULTO_FORMULARIOS_LISTAR,
        self::ADM_AVALIADOR_OCULTO_FORMULARIOS_CRIAR,
        self::ADM_AVALIADOR_OCULTO_FORMULARIOS_EDITAR,
        self::ADM_AVALIADOR_OCULTO_FORMULARIOS_DELETAR,
    ];

    const ADM_AVALIADOR_OCULTO_DASHBOARD = 88;

    const PUB_PROGRAMA_QUALIDADE_ACOES_CORRETIVAS = 89;
    const ADM_PROGRAMA_QUALIDADE_CATEGORIA_LISTAR = 90;
    const ADM_PROGRAMA_QUALIDADE_CATEGORIA_CRIAR = 91;
    const ADM_PROGRAMA_QUALIDADE_CATEGORIA_EDITAR = 92;

    const ADM_PROGRAMA_QUALIDADE_SETOR_LISTAR = 93;
    const ADM_PROGRAMA_QUALIDADE_SETOR_CRIAR = 94;
    const ADM_PROGRAMA_QUALIDADE_SETOR_EDITAR = 95;

    const ADM_PROGRAMA_QUALIDADE_AVALIACAO_LISTAR = 96;
    const ADM_PROGRAMA_QUALIDADE_AVALIACAO_CRIAR = 97;
    const ADM_PROGRAMA_QUALIDADE_AVALIACAO_EDITAR = 98;

    const ADM_PROGRAMA_QUALIDADE_DASHBOARD = 99;

    const ADM_PROGRAMA_QUALIDADE_VISITA_CRIAR = 100;
    const ADM_PROGRAMA_QUALIDADE_VISITA_EDITAR = 107;
    const ADM_PROGRAMA_QUALIDADE_VISITA_DELETAR = 101;
    const ADM_PROGRAMA_QUALIDADE_VISITA_AVALIAR = 102;

    const ADM_PROGRAMA_QUALIDADE_ESTATISTICAS = 103;

    const ADM_PROGRAMA_QUALIDADE_CICLOS_LISTAR = 104;
    const ADM_PROGRAMA_QUALIDADE_CICLOS_CRIAR = 105;
    const ADM_PROGRAMA_QUALIDADE_CICLOS_EDITAR = 106;

    const ADM_PROGRAMA_QUALIDADE = [
        self::ADM_PROGRAMA_QUALIDADE_CATEGORIA_LISTAR,
        self::ADM_PROGRAMA_QUALIDADE_CATEGORIA_CRIAR,
        self::ADM_PROGRAMA_QUALIDADE_CATEGORIA_EDITAR,
        self::ADM_PROGRAMA_QUALIDADE_SETOR_LISTAR,
        self::ADM_PROGRAMA_QUALIDADE_SETOR_CRIAR,
        self::ADM_PROGRAMA_QUALIDADE_SETOR_EDITAR,
        self::ADM_PROGRAMA_QUALIDADE_AVALIACAO_LISTAR,
        self::ADM_PROGRAMA_QUALIDADE_AVALIACAO_CRIAR,
        self::ADM_PROGRAMA_QUALIDADE_AVALIACAO_EDITAR,
        self::ADM_PROGRAMA_QUALIDADE_DASHBOARD,
        self::ADM_PROGRAMA_QUALIDADE_VISITA_CRIAR,
        self::ADM_PROGRAMA_QUALIDADE_VISITA_EDITAR,
        self::ADM_PROGRAMA_QUALIDADE_VISITA_DELETAR,
        self::ADM_PROGRAMA_QUALIDADE_VISITA_AVALIAR,
        self::ADM_PROGRAMA_QUALIDADE_ESTATISTICAS,
    ];

    const ADM_PROGRAMA_QUALIDADE_ENTIDADES = [
        self::ADM_PROGRAMA_QUALIDADE_CATEGORIA_LISTAR,
        self::ADM_PROGRAMA_QUALIDADE_CATEGORIA_CRIAR,
        self::ADM_PROGRAMA_QUALIDADE_CATEGORIA_EDITAR,
        self::ADM_PROGRAMA_QUALIDADE_SETOR_LISTAR,
        self::ADM_PROGRAMA_QUALIDADE_SETOR_CRIAR,
        self::ADM_PROGRAMA_QUALIDADE_SETOR_EDITAR,
        self::ADM_PROGRAMA_QUALIDADE_AVALIACAO_LISTAR,
        self::ADM_PROGRAMA_QUALIDADE_AVALIACAO_CRIAR,
        self::ADM_PROGRAMA_QUALIDADE_AVALIACAO_EDITAR,
    ];

    const ADM_METAS_CRIAR = 115;
    const ADM_METAS_EDITAR = 116;
    const ADM_METAS_DELETAR = 117;
    const ADM_METAS_ATIVIDADE_CRIAR = 118;

    const ADM_METAS = [
        self::ADM_METAS_CRIAR,
        self::ADM_METAS_EDITAR,
        self::ADM_METAS_DELETAR,
    ];

    const ADM_PERSONIFICACAO = 112;

    const RECURSOS = [
        self::SUPER_ADMIN => 'Super Administrador, pode tudo dentro do sistema',
        self::PUB_CANAL_FRANQUEADO => 'Acesso ao canal do franqueado',
        self::PUB_COMUNICADOS => 'Acesso aos comunicados',
        self::PUB_ARQUIVOS => 'Acesso aos arquivos',
        self::PUB_SOLICITACOES => 'Acesso as solicita????es (fazer solicita????o e acompanhamento de suas solicita????es)',
        self::PUB_PEDIDOS => 'Acesso ao m??dulo de Pedidos',
        self::PUB_ENQUETES => 'Acesso as enquetes',
        self::PUB_BUSCA => 'Acesso ao m??dulo de busca',
        self::PUB_PROGRAMA_QUALIDADE_ACOES_CORRETIVAS => 'Acesso a lista de a????es corretivas de sua loja',
        self::PUB_CLIENTE_LOJA_LISTAR => 'Clientes de loja - Listar',
        self::PUB_CLIENTE_LOJA_CRIAR => 'Clientes de loja - Criar',
        self::PUB_CLIENTE_LOJA_EDITAR => 'Clientes de loja - Editar',
        self::PUB_CLIENTE_LOJA_DELETAR => 'Clientes de loja - Deletar',
        self::PUB_METAS => 'Metas da loja',
        self::ADM_VER_MENU => 'Ver menu de administra????o',
        self::ADM_COMUNICADOS_LISTAR => '(Administra????o) Comunicados - Listar',
        self::ADM_COMUNICADOS_CRIAR => '(Administra????o) Comunicados - Criar',
        self::ADM_COMUNICADOS_EDITAR => '(Administra????o) Comunicados - Editar',
        self::ADM_COMUNICADOS_DELETAR => '(Administra????o) Comunicados - Deletar',
        self::ADM_COMUNICADOS_VER_DESTINATARIO => '(Administra????o) Comunicados - Ver destinat??rios',
        self::ADM_PASTAS_LISTAR => '(Administra????o) Pastas - Listar',
        self::ADM_PASTAS_CRIAR => '(Administra????o) Pastas - Criar',
        self::ADM_PASTAS_EDITAR => '(Administra????o) Pastas - Editar',
        self::ADM_PASTAS_DELETAR => '(Administra????o) Pastas - Deletar',
        self::ADM_PASTAS_ARQUIVOS_REMANEJAR => '(Administra????o) Pastas - Remanejar arquivos (ao deletar pasta)',
        self::ADM_PASTAS_ARQUIVOS_DELETAR => '(Administra????o) Pastas - Deletar arquivos (ao deletar pasta)',
        self::ADM_ARQUIVOS_LISTAR => '(Administra????o) Arquivos - Listar',
        self::ADM_ARQUIVOS_CRIAR => '(Administra????o) Arquivos - Criar',
        self::ADM_ARQUIVOS_EDITAR => '(Administra????o) Arquivos - Editar',
        self::ADM_ARQUIVOS_DELETAR => '(Administra????o) Arquivos - Deletar',
        self::ADM_ARQUIVOS_VER_DOWNLOADS => '(Administra????o) Arquivos - Ver quem baixou',
        self::ADM_ARQUIVOS_VER_URL => '(Administra????o) Arquivos - Ver URL',
        self::ADM_VITRINES_LISTAR => '(Administra????o) Vitrine - Listar',
        self::ADM_VITRINES_CRIAR => '(Administra????o) Vitrine - Criar',
        self::ADM_VITRINES_EDITAR => '(Administra????o) Vitrine - Editar',
        self::ADM_VITRINES_DELETAR => '(Administra????o) Vitrine - Deletar',
        self::ADM_SETORES_LISTAR => '(Administra????o) Setores - Listar',
        self::ADM_SETORES_CRIAR => '(Administra????o) Setores - Criar',
        self::ADM_SETORES_EDITAR => '(Administra????o) Setores - Editar',
        self::ADM_SETORES_DELETAR => '(Administra????o) Setores - Deletar',
        self::ADM_SETORES_REMANEJAR_SOLICITACOES => '(Administra????o) Setores - Remanejar solicita????es (ao deletar setor)',
        self::ADM_SETORES_DELETAR_SOLICITACOES => '(Administra????o) Setores - Deletar solicita????es (ao deletar setor)',
        self::ADM_SOLICITACOES_LISTAR => '(Administra????o) Solicita????es - Listar',
        self::ADM_SOLICITACOES_CRIAR => '(Administra????o) Solicita????es - Criar',
        self::ADM_SOLICITACOES_EDITAR => '(Administra????o) Solicita????es - Editar',
        self::ADM_SOLICITACOES_DELETAR => '(Administra????o) Solicita????es - Deletar',
        self::ADM_TAGS_LISTAR => '(Administra????o) Tags - Listar',
        self::ADM_TAGS_CRIAR => '(Administra????o) Tags - Criar',
        self::ADM_TAGS_EDITAR => '(Administra????o) Tags - Editar',
        self::ADM_TAGS_DELETAR => '(Administra????o) Tags - Deletar',
        self::ADM_TAGS_VIDEOS_REMANEJAR => '(Administra????o) Tags - Remanejar v??deos (ao deletar tag)',
        self::ADM_TAGS_VIDEOS_DELETAR => '(Administra????o) Tags - Deletar v??deos (ao deletar tag)',
        self::ADM_VIDEOS_LISTAR => '(Administra????o) V??deos - Listar',
        self::ADM_VIDEOS_CRIAR => '(Administra????o) V??deos - Criar',
        self::ADM_VIDEOS_EDITAR => '(Administra????o) V??deos - Editar',
        self::ADM_VIDEOS_DELETAR => '(Administra????o) V??deos - Deletar',
        self::ADM_VIDEOS_VER_QUEM_ASSISTIU => '(Administra????o) V??deos - Ver quem assistiu',
        self::ADM_USUARIOS_LISTAR => '(Administra????o) Usu??rios - Listar',
        self::ADM_USUARIOS_CRIAR => '(Administra????o) Usu??rios - Criar',
        self::ADM_USUARIOS_EDITAR => '(Administra????o) Usu??rios - Editar',
        self::ADM_PRACAS_LISTAR => '(Administra????o) Pra??as - Listar',
        self::ADM_PRACAS_CRIAR => '(Administra????o) Pra??as - Criar',
        self::ADM_PRACAS_EDITAR => '(Administra????o) Pra??as - Editar',
        self::ADM_LOJAS_LISTAR => '(Administra????o) Lojas - Listar',
        self::ADM_LOJAS_CRIAR => '(Administra????o) Lojas - Criar',
        self::ADM_LOJAS_EDITAR => '(Administra????o) Lojas - Editar',
        self::ADM_CATEGORIAS_LISTAR => '(Administra????o) Categorias de produtos - Listar',
        self::ADM_CATEGORIAS_CRIAR => '(Administra????o) Categorias de produtos - Criar',
        self::ADM_CATEGORIAS_EDITAR => '(Administra????o) Categorias de produtos - Editar',
        self::ADM_PRODUTOS_LISTAR => '(Administra????o) Produtos - Listar',
        self::ADM_PRODUTOS_CRIAR => '(Administra????o) Produtos - Criar',
        self::ADM_PRODUTOS_EDITAR => '(Administra????o) Produtos - Editar',
        self::ADM_FORNECIMENTO_PEDIDOS_LISTAR => '(Administra????o) Fornecimento - Listar pedidos',
        self::ADM_FORNECIMENTO_PEDIDOS_EDITAR => '(Administra????o) Fornecimento - Editar pedido',
        self::ADM_FORNECIMENTO_PEDIDOS_EXTRATO => '(Administra????o) Fornecimento - Ver extrato',
        self::ADM_FORNECIMENTO_PEDIDOS_DELETAR => '(Administra????o) Fornecimento - Deletar pedido',
        self::ADM_FORNECIMENTO_PEDIDOS_MULTA_EDITAR => '(Administra????o) Fornecimento - Modificar multa',
        self::ADM_ENQUETES_LISTAR => '(Administra????o) Enquetes - Listar',
        self::ADM_ENQUETES_CRIAR => '(Administra????o) Enquetes - Criar',
        self::ADM_ENQUETES_DELETAR => '(Administra????o) Enquetes - Deletar',
        self::ADM_ENQUETES_VER_RESULTADOS => '(Administra????o) Enquetes - Ver resultados',
        self::ADM_DOWNLOAD_MAILLING => '(Administra????o) Mailling - Baixar lista de emails',
        self::ADM_GRUPOS_LISTAR => '(Administra????o) Grupos - Listar',
        self::ADM_GRUPOS_CRIAR => '(Administra????o) Grupos - Criar',
        self::ADM_GRUPOS_EDITAR => '(Administra????o) Grupos - Editar',
        self::ADM_GRUPOS_DELETAR => '(Administra????o) Grupos - Deletar',
        self::ADM_AVALIADOR_OCULTO_USUARIOS_LISTAR => '(Avaliador Oculto) - Usu??rios - Listar',
        self::ADM_AVALIADOR_OCULTO_USUARIOS_CRIAR => '(Avaliador Oculto) - Usu??rios - Criar',
        self::ADM_AVALIADOR_OCULTO_USUARIOS_EDITAR => '(Avaliador Oculto) - Usu??rios - Editar',
        self::ADM_AVALIADOR_OCULTO_USUARIOS_DELETAR => '(Avaliador Oculto) - Usu??rios - Deletar',
        self::ADM_AVALIADOR_OCULTO_FORMULARIOS_LISTAR => '(Avaliador Oculto) - Formularios - Listar',
        self::ADM_AVALIADOR_OCULTO_FORMULARIOS_CRIAR => '(Avaliador Oculto) - Formularios - Criar',
        self::ADM_AVALIADOR_OCULTO_FORMULARIOS_EDITAR => '(Avaliador Oculto) - Formularios - Editar',
        self::ADM_AVALIADOR_OCULTO_FORMULARIOS_DELETAR => '(Avaliador Oculto) - Formularios - Deletar',
        self::ADM_AVALIADOR_OCULTO_DASHBOARD => '(Avaliador Oculto) - Dashboard',

        self::ADM_PROGRAMA_QUALIDADE_CICLOS_LISTAR => '(Programa de Qualidade) - Ciclos - Listar',
        self::ADM_PROGRAMA_QUALIDADE_CICLOS_CRIAR => '(Programa de Qualidade) - Ciclos - Criar',
        self::ADM_PROGRAMA_QUALIDADE_CICLOS_EDITAR => '(Programa de Qualidade) - Ciclos - Editar',

        self::ADM_PROGRAMA_QUALIDADE_CATEGORIA_LISTAR => '(Programa de Qualidade) - Categorias - Listar',
        self::ADM_PROGRAMA_QUALIDADE_CATEGORIA_CRIAR => '(Programa de Qualidade) - Categorias - Criar',
        self::ADM_PROGRAMA_QUALIDADE_CATEGORIA_EDITAR => '(Programa de Qualidade) - Categorias - Editar',

        self::ADM_PROGRAMA_QUALIDADE_SETOR_LISTAR => '(Programa de Qualidade) - Setores - Listar',
        self::ADM_PROGRAMA_QUALIDADE_SETOR_CRIAR => '(Programa de Qualidade) - Setores - Criar',
        self::ADM_PROGRAMA_QUALIDADE_SETOR_EDITAR => '(Programa de Qualidade) - Setores - Editar',

        self::ADM_PROGRAMA_QUALIDADE_AVALIACAO_LISTAR => '(Programa de Qualidade) - Avalia????es - Listar',
        self::ADM_PROGRAMA_QUALIDADE_AVALIACAO_CRIAR => '(Programa de Qualidade) - Avalia????es - Criar',
        self::ADM_PROGRAMA_QUALIDADE_AVALIACAO_EDITAR => '(Programa de Qualidade) - Avalia????es - Editar',

        self::ADM_PROGRAMA_QUALIDADE_DASHBOARD => '(Programa de Qualidade) - Acesso ao dashboard',
        self::ADM_PROGRAMA_QUALIDADE_ESTATISTICAS => '(Programa de Qualidade) - Acesso as estat??sticas',

        self::ADM_PROGRAMA_QUALIDADE_VISITA_CRIAR => '(Programa de Qualidade) - Visitas - Criar',
        self::ADM_PROGRAMA_QUALIDADE_VISITA_EDITAR => '(Programa de Qualidade) - Visitas - Editar',
        self::ADM_PROGRAMA_QUALIDADE_VISITA_DELETAR => '(Programa de Qualidade) - Visitas - Deletar',
        self::ADM_PROGRAMA_QUALIDADE_VISITA_AVALIAR => '(Programa de Qualidade) - Visitas - Avaliar',

        self::ADM_PERSONIFICACAO => '(Personificacao) - Personificar outros usu??rios',

        self::ADM_METAS_CRIAR => '(Administra????o) - Metas - Criar',
        self::ADM_METAS_EDITAR => '(Administra????o) - Metas - Editar',
        self::ADM_METAS_DELETAR => '(Administra????o) - Metas - Deletar',
        self::ADM_METAS_ATIVIDADE_CRIAR => '(Administra????o) - Metas - Atividades - Criar',
    ];

    const RECURSOS_AGRUPADOS = [
        'Super Administrador' => [
            self::SUPER_ADMIN => 'Super Administrador, pode tudo dentro do sistema',
        ],
        'Personifica????o' => [
            self::ADM_PERSONIFICACAO => 'Personificar outros usu??rios',
        ],
        '??rea comum do portal' => [
            self::PUB_CANAL_FRANQUEADO => 'Acesso ao canal do franqueado',
            self::PUB_COMUNICADOS => 'Acesso aos comunicados',
            self::PUB_ARQUIVOS => 'Acesso aos arquivos',
            self::PUB_SOLICITACOES => 'Acesso as solicita????es (fazer solicita????o e acompanhamento de suas solicita????es)',
            self::PUB_PEDIDOS => 'Acesso ao m??dulo de Pedidos',
            self::PUB_ENQUETES => 'Acesso as enquetes',
            self::PUB_BUSCA => 'Acesso ao m??dulo de busca',
            self::PUB_PROGRAMA_QUALIDADE_ACOES_CORRETIVAS => 'Acesso a lista de a????es corretivas de sua loja',
            self::PUB_CLIENTE_LOJA_LISTAR => 'Clientes de loja - Listar',
            self::PUB_CLIENTE_LOJA_CRIAR => 'Clientes de loja - Criar',
            self::PUB_CLIENTE_LOJA_EDITAR => 'Clientes de loja - Editar',
            self::PUB_CLIENTE_LOJA_DELETAR => 'Clientes de loja - Deletar',
            self::PUB_METAS => 'Metas da loja',
        ],
        'Menu de administra????o' => [
            self::ADM_VER_MENU => 'Ver menu de administra????o',
        ],
        'Administra????o - Comunicados' => [
            self::ADM_COMUNICADOS_LISTAR => 'Comunicados - Listar',
            self::ADM_COMUNICADOS_CRIAR => 'Comunicados - Criar',
            self::ADM_COMUNICADOS_EDITAR => 'Comunicados - Editar',
            self::ADM_COMUNICADOS_DELETAR => 'Comunicados - Deletar',
            self::ADM_COMUNICADOS_VER_DESTINATARIO => 'Comunicados - Ver destinat??rios',
        ],
        'Administra????o - Pastas' => [
            self::ADM_PASTAS_LISTAR => 'Pastas - Listar',
            self::ADM_PASTAS_CRIAR => 'Pastas - Criar',
            self::ADM_PASTAS_EDITAR => 'Pastas - Editar',
            self::ADM_PASTAS_DELETAR => 'Pastas - Deletar',
            self::ADM_PASTAS_ARQUIVOS_REMANEJAR => 'Pastas - Remanejar arquivos (ao deletar pasta)',
            self::ADM_PASTAS_ARQUIVOS_DELETAR => 'Pastas - Deletar arquivos (ao deletar pasta)',
        ],
        'Administra????o - Arquivos' => [
            self::ADM_ARQUIVOS_LISTAR => 'Arquivos - Listar',
            self::ADM_ARQUIVOS_CRIAR => 'Arquivos - Criar',
            self::ADM_ARQUIVOS_EDITAR => 'Arquivos - Editar',
            self::ADM_ARQUIVOS_DELETAR => 'Arquivos - Deletar',
            self::ADM_ARQUIVOS_VER_DOWNLOADS => 'Arquivos - Ver quem baixou',
            self::ADM_ARQUIVOS_VER_URL => 'Arquivos - Ver URL',
        ],
        'Administra????o - Vitrine' => [
            self::ADM_VITRINES_LISTAR => 'Vitrine - Listar',
            self::ADM_VITRINES_CRIAR => 'Vitrine - Criar',
            self::ADM_VITRINES_EDITAR => 'Vitrine - Editar',
            self::ADM_VITRINES_DELETAR => 'Vitrine - Deletar',
        ],
        'Administra????o - Setores' => [
            self::ADM_SETORES_LISTAR => 'Setores - Listar',
            self::ADM_SETORES_CRIAR => 'Setores - Criar',
            self::ADM_SETORES_EDITAR => 'Setores - Editar',
            self::ADM_SETORES_DELETAR => 'Setores - Deletar',
            self::ADM_SETORES_REMANEJAR_SOLICITACOES => 'Setores - Remanejar solicita????es (ao deletar setor)',
            self::ADM_SETORES_DELETAR_SOLICITACOES => 'Setores - Deletar solicita????es (ao deletar setor)',
        ],
        'Administra????o - Solicita????es' => [
            self::ADM_SOLICITACOES_LISTAR => 'Solicita????es - Listar',
            self::ADM_SOLICITACOES_CRIAR => 'Solicita????es - Criar',
            self::ADM_SOLICITACOES_EDITAR => 'Solicita????es - Editar',
            self::ADM_SOLICITACOES_DELETAR => 'Solicita????es - Deletar',
        ],
        'Administra????o - Tags' => [
            self::ADM_TAGS_LISTAR => 'Tags - Listar',
            self::ADM_TAGS_CRIAR => 'Tags - Criar',
            self::ADM_TAGS_EDITAR => 'Tags - Editar',
            self::ADM_TAGS_DELETAR => 'Tags - Deletar',
            self::ADM_TAGS_VIDEOS_REMANEJAR => 'Tags - Remanejar v??deos (ao deletar tag)',
            self::ADM_TAGS_VIDEOS_DELETAR => 'Tags - Deletar v??deos (ao deletar tag)',
        ],
        'Administra????o - V??deos' => [
            self::ADM_VIDEOS_LISTAR => 'V??deos - Listar',
            self::ADM_VIDEOS_CRIAR => 'V??deos - Criar',
            self::ADM_VIDEOS_EDITAR => 'V??deos - Editar',
            self::ADM_VIDEOS_DELETAR => 'V??deos - Deletar',
            self::ADM_VIDEOS_VER_QUEM_ASSISTIU => 'V??deos - Ver quem assistiu',
        ],

        'Administra????o - Usu??rios' => [
            self::ADM_USUARIOS_LISTAR => 'Usu??rios - Listar',
            self::ADM_USUARIOS_CRIAR => 'Usu??rios - Criar',
            self::ADM_USUARIOS_EDITAR => 'Usu??rios - Editar',
        ],
        'Administra????o - Pra??as' => [
            self::ADM_PRACAS_LISTAR => 'Pra??as - Listar',
            self::ADM_PRACAS_CRIAR => 'Pra??as - Criar',
            self::ADM_PRACAS_EDITAR => 'Pra??as - Editar',
        ],
        'Administra????o - Lojas (Franquias)' => [
            self::ADM_LOJAS_LISTAR => 'Lojas - Listar',
            self::ADM_LOJAS_CRIAR => 'Lojas - Criar',
            self::ADM_LOJAS_EDITAR => 'Lojas - Editar',
        ],
        'Administra????o - Categoria de Produtos' => [
            self::ADM_CATEGORIAS_LISTAR => 'Categorias de produtos - Listar',
            self::ADM_CATEGORIAS_CRIAR => 'Categorias de produtos - Criar',
            self::ADM_CATEGORIAS_EDITAR => 'Categorias de produtos - Editar',
        ],
        'Administra????o - Produtos' => [
            self::ADM_PRODUTOS_LISTAR => 'Produtos - Listar',
            self::ADM_PRODUTOS_CRIAR => 'Produtos - Criar',
            self::ADM_PRODUTOS_EDITAR => 'Produtos - Editar',
        ],
        'Administra????o - Fornecimento' => [
            self::ADM_FORNECIMENTO_PEDIDOS_LISTAR => 'Fornecimento - Listar pedidos',
            self::ADM_FORNECIMENTO_PEDIDOS_EDITAR => 'Fornecimento - Editar pedido',
            self::ADM_FORNECIMENTO_PEDIDOS_EXTRATO => 'Fornecimento - Ver extrato',
            self::ADM_FORNECIMENTO_PEDIDOS_DELETAR => 'Fornecimento - Deletar pedido',
            self::ADM_FORNECIMENTO_PEDIDOS_MULTA_EDITAR => 'Fornecimento - Modificar multa',
        ],
        'Administra????o - Enquetes' => [
            self::ADM_ENQUETES_LISTAR => 'Enquetes - Listar',
            self::ADM_ENQUETES_CRIAR => 'Enquetes - Criar',
            self::ADM_ENQUETES_DELETAR => 'Enquetes - Deletar',
            self::ADM_ENQUETES_VER_RESULTADOS => 'Enquetes - Ver resultados',
        ],
        'Administra????o - Mailling' => [
            self::ADM_DOWNLOAD_MAILLING => 'Mailling - Baixar lista de emails de clientes',
        ],
        'Administra????o - Grupos' => [
            self::ADM_GRUPOS_LISTAR => 'Grupos - Listar',
            self::ADM_GRUPOS_CRIAR => 'Grupos - Criar',
            self::ADM_GRUPOS_EDITAR => 'Grupos - Editar',
            self::ADM_GRUPOS_DELETAR => 'Grupos - Deletar',
        ],
        'Avaliador Oculto' => [
            self::ADM_AVALIADOR_OCULTO_DASHBOARD => 'Dashboard',
            self::ADM_AVALIADOR_OCULTO_USUARIOS_LISTAR => 'Usu??rios - Listar',
            self::ADM_AVALIADOR_OCULTO_USUARIOS_CRIAR => 'Usu??rios - Criar',
            self::ADM_AVALIADOR_OCULTO_USUARIOS_EDITAR => 'Usu??rios - Editar',
            self::ADM_AVALIADOR_OCULTO_USUARIOS_DELETAR => 'Usu??rios - Deletar',
            self::ADM_AVALIADOR_OCULTO_FORMULARIOS_LISTAR => 'Formularios - Listar',
            self::ADM_AVALIADOR_OCULTO_FORMULARIOS_CRIAR => 'Formularios - Criar',
            self::ADM_AVALIADOR_OCULTO_FORMULARIOS_EDITAR => 'Formularios - Editar',
            self::ADM_AVALIADOR_OCULTO_FORMULARIOS_DELETAR => 'Formularios - Deletar',
        ],
        'Programa de qualidade' => [
            self::ADM_PROGRAMA_QUALIDADE_CICLOS_LISTAR => 'Ciclos - Listar',
            self::ADM_PROGRAMA_QUALIDADE_CICLOS_CRIAR => 'Ciclos - Criar',
            self::ADM_PROGRAMA_QUALIDADE_CICLOS_EDITAR => 'Ciclos - Editar',

            self::ADM_PROGRAMA_QUALIDADE_CATEGORIA_LISTAR => 'Categorias - Listar',
            self::ADM_PROGRAMA_QUALIDADE_CATEGORIA_CRIAR => 'Categorias - Criar',
            self::ADM_PROGRAMA_QUALIDADE_CATEGORIA_EDITAR => 'Categorias - Editar',

            self::ADM_PROGRAMA_QUALIDADE_SETOR_LISTAR => 'Setores - Listar',
            self::ADM_PROGRAMA_QUALIDADE_SETOR_CRIAR => 'Setores - Criar',
            self::ADM_PROGRAMA_QUALIDADE_SETOR_EDITAR => 'Setores - Editar',

            self::ADM_PROGRAMA_QUALIDADE_AVALIACAO_LISTAR => 'Avalia????es - Listar',
            self::ADM_PROGRAMA_QUALIDADE_AVALIACAO_CRIAR => 'Avalia????es - Criar',
            self::ADM_PROGRAMA_QUALIDADE_AVALIACAO_EDITAR => 'Avalia????es - Editar',

            self::ADM_PROGRAMA_QUALIDADE_DASHBOARD => 'Acesso ao dashboard',
            self::ADM_PROGRAMA_QUALIDADE_ESTATISTICAS => 'Acesso as estat??sticas',

            self::ADM_PROGRAMA_QUALIDADE_VISITA_CRIAR => 'Visitas - Criar',
            self::ADM_PROGRAMA_QUALIDADE_VISITA_EDITAR => 'Visitas - Editar',
            self::ADM_PROGRAMA_QUALIDADE_VISITA_DELETAR => 'Visitas - Deletar',
            self::ADM_PROGRAMA_QUALIDADE_VISITA_AVALIAR => 'Visitas - Avaliar',
        ],
        'Administra????o - Metas' => [
            self::ADM_METAS_CRIAR => 'Metas - Criar',
            self::ADM_METAS_EDITAR => 'Metas - Editar',
            self::ADM_METAS_DELETAR => 'Metas - Deletar',
            self::ADM_METAS_ATIVIDADE_CRIAR => 'Metas - Atividades - Criar',
        ],
    ];

    protected $fillable = ['id', 'descricao'];

    public function grupos()
    {
        return $this->belongsToMany(Grupo::class, 'grupo_recurso');
    }
}
