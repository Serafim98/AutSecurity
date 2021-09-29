@extends('errors::minimal')

@section('title', __('Acesso Negado'))
@section('code', '403')
@section('message', __($exception->getMessage() ?: 'Você não possui acesso a este conteúdo!'))
