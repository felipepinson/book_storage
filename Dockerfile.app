FROM node:20-alpine

WORKDIR /var/www/app

COPY frontend/package.json frontend/package-lock.json ./

RUN npm install react-bootstrap bootstrap

RUN npm install

COPY frontend/ .

CMD ["npm", "run", "dev", "--", "--host"]
