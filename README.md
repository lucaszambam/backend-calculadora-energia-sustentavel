# 🌱 Calculadora de Energia Sustentável — Backend

Este repositório contém o **backend em Laravel + PostgreSQL** do projeto **Calculadora de Energia Sustentável**, desenvolvido para permitir a simulação de economia financeira e ambiental com a adoção de fontes de energia renovável (Solar e Eólica).

O sistema oferece:
- Simulação de economia com base em dados locais de tarifas e eficiência.
- Persistência das simulações, com dados de contato do interessado.
- Área administrativa protegida, onde é possível:
  - Consultar e filtrar simulações realizadas.
  - Atualizar status de contato com usuários.
  - Cadastrar e editar parâmetros técnicos (tarifas, taxas, emissões e eficiências por segmento).
  - Gerenciar administradores do sistema.

---

## 🚀 Tecnologias Utilizadas
- [Laravel 11](https://laravel.com/) — Framework PHP para backend.
- [PostgreSQL](https://www.postgresql.org/) — Banco de dados relacional.
- [Bootstrap 5](https://getbootstrap.com/) — Framework CSS para estilização do painel administrativo.
- [Laravel Sanctum](https://laravel.com/docs/sanctum) — Autenticação de APIs e proteção de rotas.
- [Blade](https://laravel.com/docs/blade) — Template engine do Laravel.

---

## ⚙️ Requisitos
- PHP >= 8.1
- Composer
- PostgreSQL >= 16

---

## 📦 Instalação e Configuração

1. Clone o repositório:
   ```bash
   git clone https://github.com/lucaszambam/backend-calculadora-energia-sustentavel
   cd backend-calculadora-energia-sustentavel
