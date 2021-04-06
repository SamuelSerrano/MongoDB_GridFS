<NominaIndividual>
    <UBLExtensions>Grupo firma digital</UBLExtensions>
    @isset($value['nie204'])
        <Novedad CUNENov="{{ $value['nie204'] }}" />{{ $value['nie199'] ?? '' }}
    @endisset

    <Periodo FechaIngreso="{{ $value['nie002'] }}" FechaRetiro="{{ $value['nie003'] ?? '' }}"
        FechaLiquidacionInicio="{{ $value['nie004'] }}" FechaLiquidacionFin="{{ $value['nie005'] }}"
        TiempoLaborado="{{ $value['nie006'] }}" FechaGen="{{ date('Y-m-d') }}" />{{-- $value['nie008'] --}}

    <NumeroSecuenciaXML CodigoTrabajador="{{ $value['nie009'] ?? '' }}" Prefijo="{{ $value['nie010'] ?? '' }}"
        Consecutivo="{{ $value['nie011'] }}" Numero="{{ $value['nie012'] }}" />

    <LugarGeneracionXML Pais="{{ $value['nie013'] }}" DepartamentoEstado="{{ $value['nie014'] }}"
        MunicipioCiudad="{{ $value['nie015'] }}" Idioma="{{ $value['nie016'] }}" />

    <ProveedorXML RazonSocial="{{ $value['nie205'] ?? '' }}" PrimerApellido="{{ $value['nie206'] ?? '' }}"
        SegundoApellido="{{ $value['nie207'] ?? '' }}" PrimerNombre="{{ $value['nie208'] ?? '' }}"
        OtrosNombres="{{ $value['nie209'] ?? '' }}" NIT="{{ $value['nie017'] }}" DV="{{ $value['nie018'] }}"
        SoftwareID="{{ $value['nie019'] }}" SoftwareSC="{{ $value['nie020'] }}"
        CodigoQR="https://catalogovpfe.dian.gov.co/document/searchqr?documentkey={{ $value['nie021'] }}" />

    <InformacionGeneral Version="{{ $value['nie022'] ?? 'V1.0: Documento Soporte de Pago de Nómina Electrónica' }}"
        Ambiente="{{ $value['nie023'] }}" TipoXML="{{ $value['nie202'] ?? '102' }}"
        CUNE="{{ $value['nie024'] }}" EncripCUNE="{{ $value['nie025'] ?? 'CUNE‐SHA384' }}"
        FechaGen="{{ $value['nie026'] ?? date('Y-m-d') }}" HoraGen="{{ $value['nie027'] ?? date('H:i:sP') }}"
        PeriodoNomina="{{ $value['nie029'] }}" TipoMoneda="{{ $value['nie030'] ?? 'COP' }}"
        TRM="{{ $value['nie200'] ?? '' }}" />

    @if (!empty($value['nie031']))
        <Notas />{{ $value['nie031'] }}
    @endif


    <Empleador RazonSocial="{{ $value['nie032'] ?? '' }}" PrimerApellido="{{ $value['nie210'] ?? '' }}"
        SegundoApellido="{{ $value['nie211'] ?? '' }}" PrimerNombre="{{ $value['nie212'] ?? '' }}"
        OtrosNombres="{{ $value['nie213'] ?? '' }}" NIT="{{ $value['nie033'] }}" DV="{{ $value['nie034'] }}"
        Pais="{{ $value['nie035'] }}" DepartamentoEstado="{{ $value['nie036'] }}"
        MunicipioCiudad="{{ $value['nie037'] }}" Direccion="{{ $value['nie038'] }}">
    </Empleador>

    <Trabajador TipoTrabajador="{{ Str::limit($value['nie041'], $limit = 2, $end = '') }}"
        SubTipoTrabajador="{{ Str::limit($value['nie042'], $limit = 2, $end = '') }}"
        AltoRiesgoPension="{{ $value['nie043'] }}" TipoDocumento="{{ $value['nie044'] }}"
        NumeroDocumento="{{ $value['nie045'] }}" PrimerApellido="{{ $value['nie046'] }}"
        SegundoApellido="{{ $value['nie047'] }}" PrimerNombre="{{ $value['nie048'] }}"
        OtrosNombres="{{ $value['nie049'] ?? '' }}" LugarTrabajoPais="{{ $value['nie050'] }}"
        LugarTrabajoDepartamentoEstado="{{ $value['nie051'] }}"
        LugarTrabajoMunicipioCiudad="{{ $value['nie052'] }}" LugarTrabajoDireccion="{{ $value['nie053'] }}"
        SalarioIntegral="{{ $value['nie056'] }}" TipoContrato="{{ $value['nie061'] }}"
        Sueldo="{{ $value['nie062'] }}" CodigoTrabajador="{{ $value['nie063'] ?? '' }}" />

    <Pago Forma="{{ $value['nie064'] }}" Metodo="{{ $value['nie065'] }}" Banco="{{ $value['nie066'] ?? '' }}"
        TipoCuenta="{{ $value['nie067'] ?? '' }}" NumeroCuenta="{{ $value['nie068'] ?? '' }}" />

    <FechasPagos>
        <FechaPago> {{ $value['nie203'] }} </FechaPago>
    </FechasPagos>

    <Devengados>
        <Basico DiasTrabajados="{{ $value['nie069'] }}" SueldoTrabajado="{{ $value['nie070'] }}" />

        @if ($value['aplicaviaticos'] === 'Si' && !empty($value['nie071']))
            <Transporte AuxilioTransporte="{{ $value['nie071'] ?? '' }}"
                ViaticoManuAlojS="{{ $value['nie072'] ?? '' }}"
                ViaticoManuAlojNS="{{ $value['nie073'] ?? '' }}" />
        @endif

        @if ($value['aplicahorasextras'] === 'Si')
            @if (!empty($value['nie076']) && !empty($value['nie077']) && !empty($value['nie078']))
                <HEDs>
                    <HED HoraInicio="{{ $value['nie074'] ?? '' }}" HoraFin="{{ $value['nie075'] ?? '' }}"
                        Cantidad="{{ $value['nie076'] }}" Porcentaje="{{ $value['nie077'] }}"
                        Pago="{{ $value['nie078'] }}" />
                </HEDs>
            @endif

            @if (!empty($value['nie081']) && !empty($value['nie082']) && !empty($value['nie083']))
                <HENs>
                    <HEN HoraInicio="{{ $value['nie079'] ?? '' }}" HoraFin="{{ $value['nie080'] ?? '' }}"
                        Cantidad="{{ $value['nie081'] }}" Porcentaje="{{ $value['nie082'] }}"
                        Pago="{{ $value['nie083'] }}" />
                </HENs>
            @endif

            @if (!empty($value['nie086']) && !empty($value['nie087']) && !empty($value['nie088']))
                <HRNs>
                    <HRN HoraInicio="{{ $value['nie084'] ?? '' }}" HoraFin="{{ $value['nie085'] ?? '' }}"
                        Cantidad="{{ $value['nie086'] }}" Porcentaje="{{ $value['nie087'] }}"
                        Pago="{{ $value['nie088'] }}" />
                </HRNs>
            @endif

            @if (!empty($value['nie091']) && !empty($value['nie092']) && !empty($value['nie093']))
                <HEDDFs>
                    <HEDDF HoraInicio="{{ $value['nie089'] ?? '' }}" HoraFin="{{ $value['nie090'] ?? '' }}"
                        Cantidad="{{ $value['nie091'] }}" Porcentaje="{{ $value['nie092'] }}"
                        Pago="{{ $value['nie093'] }}" />
                </HEDDFs>
            @endif

            @if (!empty($value['nie096']) && !empty($value['nie097']) && !empty($value['nie098']))
                <HRDDFs>
                    <HRDDF HoraInicio="{{ $value['nie094'] ?? '' }}" HoraFin="{{ $value['nie095'] ?? '' }}"
                        Cantidad="{{ $value['nie096'] }}" Porcentaje="{{ $value['nie097'] }}"
                        Pago="{{ $value['nie098'] }}" />
                </HRDDFs>
            @endif

            @if (!empty($value['nie101']) && !empty($value['nie102']) && !empty($value['nie103']))
                <HENDFs>
                    <HENDF HoraInicio="{{ $value['nie099'] ?? '' }}" HoraFin="{{ $value['nie100'] ?? '' }}"
                        Cantidad="{{ $value['nie101'] }}" Porcentaje="{{ $value['nie102'] }}"
                        Pago="{{ $value['nie103'] }}" />
                </HENDFs>
            @endif

            @if (!empty($value['nie106']) && !empty($value['nie107']) && !empty($value['nie108']))
                <HRNDFs>
                    <HRNDF HoraInicio="{{ $value['nie104'] ?? '' }}" HoraFin="{{ $value['nie105'] ?? '' }}"
                        Cantidad="{{ $value['nie106'] }}" Porcentaje="{{ $value['nie107'] }}"
                        Pago="{{ $value['nie108'] }}" />
                </HRNDFs>
            @endif
        @endif

        @if ($value['aplicavacaciones'] === 'Si')
            <Vacaciones>
                @if (!empty($value['nie111']) && !empty($value['nie112']))
                    <VacacionesComunes FechaInicio="{{ $value['nie109'] ?? '' }}"
                        FechaFin="{{ $value['nie110'] ?? '' }}" Cantidad="{{ $value['nie111'] }}"
                        Pago="{{ $value['nie112'] }}" />
                @endif

                @if (!empty($value['nie115']) && !empty($value['nie116']))
                    <VacacionesCompensadas Cantidad="{{ $value['nie115'] }}" Pago="{{ $value['nie116'] }}" />
                @endif
            </Vacaciones>
        @endif

        @if ($value['aplicaprimas'] === 'Si' && !empty($value['nie117']) && !empty($value['nie118']))
            <Primas Cantidad="{{ $value['nie117'] }}" Pago="{{ $value['nie118'] }}"
                PagoNS="{{ $value['nie119'] ?? '' }}" />
        @endif

        @if ($value['aplicacesantias'] === 'Si' && !empty($value['nie120']) && !empty($value['nie121']) && !empty($value['nie122']))
            <Cesantias Pago="{{ $value['nie120'] }}" Porcentaje="{{ $value['nie121'] }}"
                PagoIntereses="{{ $value['nie122'] }}" />
        @endif

        @if ($value['aplicaincapacidad'] === 'Si' && !empty($value['nie125']) && !empty($value['nie126']) && !empty($value['nie127']))
            <Incapacidades>
                <Incapacidad FechaInicio="{{ $value['nie123'] ?? '' }}" FechaFin="{{ $value['nie124'] ?? '' }}"
                    Cantidad="{{ $value['nie125'] }}" Tipo="{{ $value['nie126'] }}"
                    Pago="{{ $value['nie127'] }}" />
            </Incapacidades>
        @endif

        @if ($value['aplicalicenciamp'] === 'Si' || $value['aplicalicenciar'] === 'Si' || $value['aplicalicencianr'] === 'Si')
            <Licencias>
                @if ($value['aplicalicenciamp'] === 'Si')
                    <LicenciaMP FechaInicio="{{ $value['nie128'] ?? '' }}"
                        FechaFin="{{ $value['nie129'] ?? '' }}" Cantidad="{{ $value['nie130'] }}"
                        Pago="{{ $value['nie131'] }}" />
                @endif
                @if ($value['aplicalicenciar'] === 'Si')
                    <LicenciaR FechaInicio="{{ $value['nie132'] ?? '' }}"
                        FechaFin="{{ $value['nie133'] ?? '' }}" Cantidad="{{ $value['nie134'] }}"
                        Pago="{{ $value['nie135'] }}" />
                @endif
                @if ($value['aplicalicencianr'] === 'Si')
                    <LicenciaNR FechaInicio="{{ $value['nie136'] ?? '' }}"
                        FechaFin="{{ $value['nie137'] ?? '' }}" Cantidad="{{ $value['nie138'] }}" />
                @endif
            </Licencias>
        @endif

        @if ($value['aplicabonificaciones'] === 'Si')
            <Bonificaciones>
                <Bonificacion BonificacionS="{{ $value['nie139'] ?? '' }}"
                    BonificacionNS="{{ $value['nie140'] ?? '' }}" />
            </Bonificaciones>
        @endif

        @if ($value['aplicaauxilios'] === 'Si')
            <Auxilios>
                <Auxilio AuxilioS="{{ $value['nie141'] ?? '' }}" AuxilioNS="{{ $value['nie142'] ?? '' }}" />
            </Auxilios>
        @endif

        @if ($value['aplicahuelgaslegales'] === 'Si' && !empty($value['nie145']))
            <HuelgasLegales>
                <HuelgaLegal FechaInicio="{{ $value['nie143'] ?? '' }}" FechaFin="{{ $value['nie144'] ?? '' }}"
                    Cantidad="{{ $value['nie145'] }}" />
            </HuelgasLegales>
        @endif

        @if ($value['aplicaotrosconceptos'] === 'Si' && !empty($value['nie146']))
            <OtrosConceptos>
                <OtroConcepto DescripcionConcepto="{{ $value['nie146'] }}"
                    ConceptoS="{{ $value['nie147'] ?? '' }}" ConceptoNS="{{ $value['nie148'] ?? '' }}" />
            </OtrosConceptos>
        @endif

        @if ($value['aplicacompensaciones'] === 'Si' && !empty($value['nie149']) && !empty($value['nie150']))
            <Compensaciones>
                <Compensacion CompensacionO="{{ $value['nie149'] }}" CompensacionE="{{ $value['nie150'] }}" />
            </Compensaciones>
        @endif

        @if ($value['aplicabonoepctvs'] === 'Si')
            <BonoEPCTVs>
                <BonoEPCTV PagoS="{{ $value['nie151'] ?? '' }}" PagoNS="{{ $value['nie152'] ?? '' }}"
                    PagoAlimentacionS="{{ $value['nie153'] ?? '' }}"
                    PagoAlimentacionNS="{{ $value['nie154'] ?? '' }}" />
            </BonoEPCTVs>
        @endif

        @if ($value['aplicacomisiones'] === 'Si')
            <Comisiones>
                <Comision>{{ $value['nie155'] ?? '' }}</Comision>
            </Comisiones>
        @endif

        @if ($value['aplicapagoterceros'] == 'Si')
            <PagosTerceros>
                <PagoTercero>{{ $value['nie193'] ?? '' }}</PagoTercero>
            </PagosTerceros>
        @endif

        @if ($value['aplicaanticipos'] == 'Si' ?? '')
            <Anticipos>
                <Anticipo>{{ $value['nie194'] }}</Anticipo>
            </Anticipos>
        @endif

        @if (!empty($value['nie156']))
            <Dotacion>{{ $value['nie156'] }}</Dotacion>
        @endif

        @if (!empty($value['nie157']))
            <ApoyoSost>{{ $value['nie157'] }}</ApoyoSost>
        @endif

        @if (!empty($value['nie158']))
            <Teletrabajo>{{ $value['nie158'] }}</Teletrabajo>
        @endif

        @if (!empty($value['nie159']))
            <BonifRetiro>{{ $value['nie159'] }}</BonifRetiro>
        @endif

        @if (!empty($value['nie160']))
            <Indemnizacion>{{ $value['nie160'] }}</Indemnizacion>
        @endif

        @if (!empty($value['nie161']))
            <Reintegro>{{ $value['nie161'] }}</Reintegro>
        @endif
    </Devengados>

    <Deducciones>
        <Salud Porcentaje="{{ $value['nie161'] }}" Deduccion="{{ $value['nie163'] }}" />
        <FondoPension Porcentaje="{{ $value['nie164'] }}" Deduccion="{{ $value['nie166'] }}" />

        @if ($value['aplicafondosp'] === 'Si')
            <FondoSP Porcentaje="{{ $value['nie167'] ?? '' }}" DeduccionSP="{{ $value['nie168'] ?? '' }}"
                PorcentajeSub="{{ $value['nie169'] ?? '' }}" DeduccionSub="{{ $value['nie170'] ?? '' }}" />
        @endif

        @if ($value['aplicasindicatos'] === 'Si' && !empty($value['nie171']) && !empty($value['nie172']))
            <Sindicatos>
                <Sindicato Porcentaje="{{ $value['nie171'] }}" Deduccion="{{ $value['nie172'] }}" />
            </Sindicatos>
        @endif

        @if ($value['aplicasanciones'] === 'Si' && !empty($value['nie173']) && !empty($value['nie174']))
            <Sanciones>
                <Sancion SancionPublic="{{ $value['nie173'] }}" SancionPriv="{{ $value['nie174'] }}" />
            </Sanciones>
        @endif

        @if ($value['aplicalibranzas'] === 'Si' && !empty($value['nie175']) && !empty($value['nie176']))
            <Libranzas>
                <Libranza Descripcion="{{ $value['nie175'] }}" Deduccion="{{ $value['nie176'] }}" />
            </Libranzas>
        @endif

        @if ($value['aplicapagoterceros'] === 'Si')
            <PagosTerceros>
                <PagoTercero>{{ $value['nie195'] ?? '' }}</PagoTercero>
            </PagosTerceros>
        @endif

        @if ($value['aplicaanticipos'] === 'Si')
            <Anticipos>
                <Anticipo>{{ $value['nie196'] ?? '' }}</Anticipo>
            </Anticipos>
        @endif

        @if ($value['aplicaotrasdeducciones'] === 'Si')
            <OtrasDeducciones>
                <OtraDeduccion>{{ $value['nie197'] }}</OtraDeduccion>
            </OtrasDeducciones>
        @endif

        @if ($value['aplicafondopension'] === 'Si')
            <PensionVoluntaria>{{ $value['nie198'] }}</PensionVoluntaria>
        @endif

        <RetencionFuente>{{ $value['nie177'] ?? '' }}</RetencionFuente>
        <AFC>{{ $value['nie179'] ?? '' }}</AFC>
        <Cooperativa>{{ $value['nie180'] ?? '' }}</Cooperativa>
        <EmbargoFiscal>{{ $value['nie181' ?? ''] }}</EmbargoFiscal>
        <PlanComplementarios>{{ $value['nie182'] ?? '' }}</PlanComplementarios>
        <Educacion>{{ $value['nie183'] ?? '' }}</Educacion>
        <Reintegro>{{ $value['nie184'] ?? '' }}</Reintegro>
        <Deuda>{{ $value['nie185'] ?? '' }}</Deuda>
    </Deducciones>
    @if (!empty($value['nie186']))
        <Redondeo>{{ $value['nie186'] }}</Redondeo>
    @endif

    <DevengadosTotal>{{ $value['nie187'] }}</DevengadosTotal>
    <DeduccionesTotal>{{ $value['nie188'] }}</DeduccionesTotal>
    <ComprobanteTotal>{{ $value['nie189'] }}</ComprobanteTotal>
</NominaIndividual>
