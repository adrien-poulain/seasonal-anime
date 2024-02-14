dbal:
        default_connection:       connec
        connections:
            connec:
                dbname: webparts
                host: 0.0.0.0
                user: test
                password: test
                server_version: '10.1.41-MariaDB-0+deb9u1'
        # IMPORTANT: You MUST configure your server version,
        # either here or in the DATABASE_URL env var (see .env file)
        #server_version: '13'
    orm:
        auto_generate_proxy_classes: true      
        entity_managers:
            connec:
                connection: connec
                naming_strategy: doctrine.orm.naming_strategy.underscore_number_aware
                mappings:
                    App:
                        is_bundle: false
                        type: annotation
                        dir: '%kernel.project_dir%/src/Entity/Connec'
                        prefix: 'App\Entity\Connec'
                        alias: App
