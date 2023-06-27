# Используем официальный образ Nginx
FROM nginx:latest

# Открываем порт 80 для веб-трафика
EXPOSE 80

USER www-data

# Запускаем Nginx в фоновом режиме при старте контейнера
CMD ["nginx", "-g", "daemon off;"]